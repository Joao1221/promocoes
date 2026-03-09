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
        AuthMiddleware::handle('lojista');
        $this->render('vendor/store-form', [
            'title' => 'Minha loja',
            'store' => (new Store())->byUser((int) Auth::user()['id']),
        ], 'layouts/panel');
    }

    public function save(): void
    {
        AuthMiddleware::handle('lojista');
        Csrf::validate($_POST['_token'] ?? null);

        $storeModel = new Store();
        $existing = $storeModel->byUser((int) Auth::user()['id']);
        $data = [
            'usuario_id' => (int) Auth::user()['id'],
            'nome_loja' => trim($_POST['nome_loja'] ?? ''),
            'slug' => slugify($_POST['nome_loja'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'whatsapp' => preg_replace('/\D+/', '', $_POST['whatsapp'] ?? ''),
            'vende_online' => (int) (($_POST['vende_online'] ?? '1') === '1'),
            'forma_pagamento' => trim($_POST['forma_pagamento'] ?? 'PIX'),
            'tem_delivery' => (int) (($_POST['tem_delivery'] ?? '0') === '1'),
            'instagram' => trim($_POST['instagram'] ?? ''),
            'logo' => null,
            'banner' => null,
            'banner_mobile' => null,
            'cidade' => trim($_POST['cidade'] ?? 'Capela'),
            'bairro' => trim($_POST['bairro'] ?? ''),
            'endereco' => trim($_POST['endereco'] ?? ''),
            'horario_funcionamento' => trim($_POST['horario_funcionamento'] ?? ''),
            'status' => $existing['status'] ?? 'aprovada',
            'destaque' => $existing['destaque'] ?? 0,
        ];

        if (Validator::required($data, ['nome_loja', 'telefone', 'whatsapp', 'bairro', 'endereco'])) {
            Session::flash('error', 'Preencha os campos obrigatorios da loja.');
            $this->redirect('lojista/loja');
        }

        if ($data['forma_pagamento'] === '') {
            $data['forma_pagamento'] = 'PIX';
        }

        try {
            $data['logo'] = Upload::image($_FILES['logo'] ?? [], __DIR__ . '/../../uploads/lojas');
            $data['banner'] = Upload::image($_FILES['banner'] ?? [], __DIR__ . '/../../uploads/lojas');
            $data['banner_mobile'] = Upload::image($_FILES['banner_mobile'] ?? [], __DIR__ . '/../../uploads/lojas');
        } catch (RuntimeException $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('lojista/loja');
        }

        if ($existing) {
            $storeModel->update((int) $existing['id'], $data);
        } else {
            $storeModel->create($data);
        }

        Session::flash('success', 'Loja publicada com sucesso.');
        $this->redirect('lojista');
    }
}
