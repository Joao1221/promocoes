<?php
class ChatbotFaq extends Model
{
    public function reply(string $message): array
    {
        $message = trim($message);
        if ($message === '') {
            return [
                'intent' => 'welcome',
                'answer' => 'Oi! Eu sou o assistente de cadastro. Posso te orientar no passo a passo para comprar, vender e criar loja.',
                'options' => $this->optionsFor('welcome'),
            ];
        }

        $normalized = $this->normalize($message);
        $intent = $this->detectIntent($normalized);
        $faq = $this->findActiveByIntent($intent) ?? $this->fallbackFaq($intent);

        if (!$faq) {
            return [
                'intent' => 'fallback',
                'answer' => 'Nao consegui identificar sua duvida com seguranca. Escolha uma opcao abaixo que eu te guio passo a passo.',
                'options' => $this->optionsFor('fallback'),
            ];
        }

        return [
            'intent' => (string) ($faq['intent'] ?? $intent),
            'answer' => (string) ($faq['resposta'] ?? ''),
            'options' => $this->optionsFor((string) ($faq['intent'] ?? $intent)),
        ];
    }

    private function findActiveByIntent(string $intent): ?array
    {
        if ($intent === 'fallback') {
            return null;
        }

        try {
            $stmt = $this->db->prepare(
                'SELECT intent, resposta
                 FROM chatbot_faqs
                 WHERE ativo = 1 AND intent = :intent
                 ORDER BY ordem ASC, id ASC
                 LIMIT 1'
            );
            $stmt->execute(['intent' => $intent]);
            return $stmt->fetch() ?: null;
        } catch (PDOException) {
            return null;
        }
    }

    private function detectIntent(string $normalizedMessage): string
    {
        if ($this->containsAny($normalizedMessage, ['fiz a compra', 'comprei', 'pedido feito', 'e agora'])) {
            return 'compra_e_agora';
        }

        if ($this->containsAny($normalizedMessage, ['o que posso vender', 'posso vender', 'o que vender'])) {
            return 'o_que_posso_vender';
        }

        if ($this->containsAny($normalizedMessage, ['o que posso comprar', 'posso comprar', 'o que comprar'])) {
            return 'o_que_posso_comprar';
        }

        if ($this->containsAny($normalizedMessage, ['como encontrar', 'barra de pesquisa', 'buscar produto', 'pesquisar produto'])) {
            return 'como_encontrar_produto';
        }

        if ($this->containsAny($normalizedMessage, ['cpf', 'cnpj', 'documento'])) {
            return 'cpf_cnpj';
        }

        if (
            $this->containsAny($normalizedMessage, ['criar loja', 'cadastrar loja', 'abrir loja']) ||
            ($this->containsAny($normalizedMessage, ['loja']) && $this->containsAny($normalizedMessage, ['logo', 'banner', 'pagina']))
        ) {
            return 'criar_loja';
        }

        if ($this->containsAny($normalizedMessage, ['endereco', 'entrega', 'receber pedido'])) {
            return 'endereco_entrega';
        }

        if ($this->containsAny($normalizedMessage, ['login', 'entrar', 'senha', 'acesso'])) {
            return 'login';
        }

        if ($this->containsAny($normalizedMessage, ['vender', 'vendedor', 'lojista', 'anunciar', 'publicar produto'])) {
            return 'cadastro_vendedor';
        }

        if ($this->containsAny($normalizedMessage, ['cadastro', 'cadastrar', 'conta', 'comprar', 'comprador'])) {
            return 'cadastro_comprador';
        }

        return 'fallback';
    }

    private function containsAny(string $text, array $needles): bool
    {
        foreach ($needles as $needle) {
            if ($needle !== '' && str_contains($text, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $text): string
    {
        $text = trim($text);
        $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = strtolower($converted);
        }

        $text = preg_replace('/[^a-z0-9\s]+/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', (string) $text);
        return trim((string) $text);
    }

    private function fallbackFaq(string $intent): ?array
    {
        $fallback = [
            'cadastro_comprador' => [
                'intent' => 'cadastro_comprador',
                'resposta' => "Passo a passo para cadastro de comprador:\n1. Clique em Crie sua conta.\n2. Preencha nome, email, telefone e senha.\n3. Conclua o cadastro.\n4. Para comprar, informe seu endereco de entrega no checkout quando fizer o primeiro pedido.",
            ],
            'cadastro_vendedor' => [
                'intent' => 'cadastro_vendedor',
                'resposta' => "Passo a passo para vender:\n1. Crie sua conta normalmente.\n2. Acesse o menu Vender.\n3. Escolha se vai vender como pessoa fisica (CPF) ou juridica (CNPJ).\n4. Complete a identificacao e cadastre sua loja, se desejar.\n5. Depois, publique seus produtos.",
            ],
            'criar_loja' => [
                'intent' => 'criar_loja',
                'resposta' => "Passo a passo para criar loja:\n1. Entre no painel Vender.\n2. Acesse Cadastro de loja.\n3. Informe nome da loja, descricao, documento (CPF/CNPJ), contato e endereco.\n4. Envie logo, banner desktop e banner mobile.\n5. Salve para concluir e depois cadastre os produtos.",
            ],
            'endereco_entrega' => [
                'intent' => 'endereco_entrega',
                'resposta' => "Endereco de entrega:\n1. Escolha produtos e va para o carrinho.\n2. Clique em Finalizar compra.\n3. Preencha o endereco completo de entrega.\n4. Confirme pagamento e conclua o pedido.",
            ],
            'cpf_cnpj' => [
                'intent' => 'cpf_cnpj',
                'resposta' => "Identificacao para vender:\n1. Se voce vende como pessoa fisica, use CPF.\n2. Se vende como empresa, use CNPJ.\n3. Preencha o numero corretamente no cadastro de loja.\n4. Guarde o documento valido para futuras validacoes.",
            ],
            'login' => [
                'intent' => 'login',
                'resposta' => "Para entrar na conta:\n1. Clique em Entre.\n2. Informe email e senha.\n3. Se ainda nao tem conta, clique em Crie sua conta e finalize o cadastro.",
            ],
            'compra_e_agora' => [
                'intent' => 'compra_e_agora',
                'resposta' => 'Um WhatsApp e enviado para o vendedor com os dados da compra, aguarde ele responder e negocie o pagamento com ele.',
            ],
            'o_que_posso_vender' => [
                'intent' => 'o_que_posso_vender',
                'resposta' => 'Tudo que voce quiser, desde um doce, ate um aviao.',
            ],
            'o_que_posso_comprar' => [
                'intent' => 'o_que_posso_comprar',
                'resposta' => 'Tudo que estiver exposto no site e seu dinheiro der.',
            ],
            'como_encontrar_produto' => [
                'intent' => 'como_encontrar_produto',
                'resposta' => 'Na barra de pesquisa, digite o nome do que voce quer comprar, se estiver a venda, vai aparecer na lista.',
            ],
        ];

        return $fallback[$intent] ?? null;
    }

    private function optionsFor(string $intent): array
    {
        $base = [
            'Como criar cadastro de comprador?',
            'Como vender no site?',
            'Como cadastrar loja?',
            'Onde informo endereco de entrega?',
            'Como funciona CPF e CNPJ para vender?',
            'Fiz a compra e agora?',
            'O que posso vender?',
            'O que posso comprar?',
            'Como encontrar o que eu quero comprar?',
        ];

        return match ($intent) {
            'cadastro_comprador' => [
                'Onde informo endereco de entrega?',
                'Como vender no site?',
                'Como cadastrar loja?',
            ],
            'cadastro_vendedor', 'criar_loja', 'cpf_cnpj' => [
                'Como cadastrar loja?',
                'Como funciona CPF e CNPJ para vender?',
                'Como criar cadastro de comprador?',
            ],
            'endereco_entrega' => [
                'Como criar cadastro de comprador?',
                'Como vender no site?',
            ],
            'login' => [
                'Como criar cadastro de comprador?',
                'Como vender no site?',
            ],
            'compra_e_agora' => [
                'O que posso comprar?',
                'Como encontrar o que eu quero comprar?',
                'Como vender no site?',
            ],
            'o_que_posso_vender' => [
                'Como vender no site?',
                'Como cadastrar loja?',
                'Como funciona CPF e CNPJ para vender?',
            ],
            'o_que_posso_comprar', 'como_encontrar_produto' => [
                'O que posso comprar?',
                'Como encontrar o que eu quero comprar?',
                'Fiz a compra e agora?',
            ],
            default => $base,
        };
    }
}
