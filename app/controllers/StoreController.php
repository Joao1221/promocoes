<?php
class StoreController extends Controller
{
    public function show(string $slug): void
    {
        $store = (new Store())->findBySlug($slug);
        if (!$store) {
            http_response_code(404);
            exit('Loja nao encontrada.');
        }

        $this->render('stores/show', [
            'title' => $store['nome_loja'],
            'store' => $store,
            'products' => (new Product())->byStore((int) $store['id']),
        ]);
    }

    public function createForm(): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista']);
        $store = (new Store())->byUser((int) Auth::user()['id']);
        $oldInput = Session::get('store_form_old');
        Session::forget('store_form_old');

        if (is_array($oldInput) && $oldInput !== []) {
            $store = array_merge($store ?? [], $oldInput);
        }

        $this->render('vendor/store-form', [
            'title' => 'Minha loja',
            'store' => $store,
            'hasStore' => $store !== null && isset($store['id']),
        ], 'layouts/panel');
    }

    public function save(): void
    {
        AuthMiddleware::handle(['consumidor', 'lojista']);
        Csrf::validate($_POST['_token'] ?? null);

        $storeModel = new Store();
        $existing = $storeModel->byUser((int) Auth::user()['id']);
        $formInput = $this->captureStoreFormInput();

        $data = [
            'usuario_id' => (int) Auth::user()['id'],
            'nome_loja' => $formInput['nome_loja'],
            'slug' => slugify($formInput['nome_loja']),
            'descricao' => $formInput['descricao'],
            'documento_tipo' => $formInput['documento_tipo'],
            'documento_numero' => $this->normalizeDocument($formInput['documento_numero']),
            'telefone' => $formInput['telefone'],
            'whatsapp' => preg_replace('/\D+/', '', $formInput['whatsapp']) ?? '',
            'vende_online' => (int) ($formInput['vende_online'] === '1'),
            'forma_pagamento' => $formInput['forma_pagamento'],
            'tem_delivery' => (int) ($formInput['tem_delivery'] === '1'),
            'instagram' => $formInput['instagram'],
            'logo' => null,
            'banner' => null,
            'banner_mobile' => null,
            'cidade' => $formInput['cidade'],
            'bairro' => $formInput['bairro'],
            'endereco' => $formInput['endereco'],
            'horario_funcionamento' => $formInput['horario_funcionamento'],
            'status' => $existing['status'] ?? 'aprovada',
            'destaque' => $existing['destaque'] ?? 0,
        ];

        if (Validator::required($data, ['nome_loja', 'documento_tipo', 'documento_numero', 'telefone', 'whatsapp', 'bairro', 'endereco'])) {
            $this->rememberStoreFormInput($formInput);
            Session::flash('error', 'Preencha os campos obrigatorios da loja.');
            $this->redirect('lojista/loja');
        }

        if (!in_array($data['documento_tipo'], ['CPF', 'CNPJ'], true)) {
            $this->rememberStoreFormInput($formInput);
            Session::flash('error', 'Selecione CPF ou CNPJ para continuar.');
            $this->redirect('lojista/loja');
        }

        if ($data['documento_tipo'] === 'CPF' && !$this->isValidCpf($data['documento_numero'])) {
            $this->rememberStoreFormInput($formInput);
            Session::flash('error', 'CPF invalido.');
            $this->redirect('lojista/loja');
        }

        if ($data['documento_tipo'] === 'CNPJ' && !$this->isValidCnpj($data['documento_numero'])) {
            $this->rememberStoreFormInput($formInput);
            Session::flash('error', 'CNPJ invalido.');
            $this->redirect('lojista/loja');
        }

        if ($data['forma_pagamento'] === '') {
            $data['forma_pagamento'] = 'PIX';
            $formInput['forma_pagamento'] = 'PIX';
        }

        try {
            $data['logo'] = Upload::image($_FILES['logo'] ?? [], __DIR__ . '/../../uploads/lojas');
            $data['banner'] = Upload::image($_FILES['banner'] ?? [], __DIR__ . '/../../uploads/lojas');
            $data['banner_mobile'] = Upload::image($_FILES['banner_mobile'] ?? [], __DIR__ . '/../../uploads/lojas');
        } catch (RuntimeException $e) {
            $this->rememberStoreFormInput($formInput);
            Session::flash('error', $e->getMessage());
            $this->redirect('lojista/loja');
        }

        if ($existing) {
            $storeModel->update((int) $existing['id'], $data);
        } else {
            $storeModel->create($data);
        }

        Session::forget('store_form_old');
        Session::flash('success', 'Loja publicada com sucesso.');
        $this->redirect('lojista');
    }

    private function rememberStoreFormInput(array $data): void
    {
        Session::set('store_form_old', $data);
    }

    private function captureStoreFormInput(): array
    {
        return [
            'nome_loja' => trim($_POST['nome_loja'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'documento_tipo' => strtoupper(trim($_POST['documento_tipo'] ?? '')),
            'documento_numero' => trim($_POST['documento_numero'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'whatsapp' => trim($_POST['whatsapp'] ?? ''),
            'vende_online' => (($_POST['vende_online'] ?? '1') === '1') ? '1' : '0',
            'forma_pagamento' => trim($_POST['forma_pagamento'] ?? 'PIX'),
            'tem_delivery' => (($_POST['tem_delivery'] ?? '0') === '1') ? '1' : '0',
            'instagram' => trim($_POST['instagram'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? 'Capela'),
            'bairro' => trim($_POST['bairro'] ?? ''),
            'endereco' => trim($_POST['endereco'] ?? ''),
            'horario_funcionamento' => trim($_POST['horario_funcionamento'] ?? ''),
        ];
    }

    private function normalizeDocument(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    private function isValidCpf(string $cpf): bool
    {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += ((int) $cpf[$c]) * (($t + 1) - $c);
            }

            $digit = ((10 * $d) % 11) % 10;
            if ((int) $cpf[$t] !== $digit) {
                return false;
            }
        }

        return true;
    }

    private function isValidCnpj(string $cnpj): bool
    {
        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ((int) $cnpj[$i]) * $weights1[$i];
        }
        $rest = $sum % 11;
        $digit1 = $rest < 2 ? 0 : 11 - $rest;

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += ((int) $cnpj[$i]) * $weights2[$i];
        }
        $rest = $sum % 11;
        $digit2 = $rest < 2 ? 0 : 11 - $rest;

        return ((int) $cnpj[12] === $digit1) && ((int) $cnpj[13] === $digit2);
    }
}
