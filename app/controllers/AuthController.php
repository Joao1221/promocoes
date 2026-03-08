<?php
class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->render('auth/login', ['title' => 'Entrar']);
    }

    public function login(): void
    {
        Csrf::validate($_POST['_token'] ?? null);
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $user = (new User())->findByEmail($email);
        if (!$user || !password_verify($senha, $user['senha'])) {
            Session::flash('error', 'Credenciais invalidas.');
            $this->redirect('login');
        }

        if ($user['status'] !== 'ativo') {
            Session::flash('error', 'Conta inativa.');
            $this->redirect('login');
        }

        unset($user['senha']);
        Auth::login($user);

        $this->redirect(match ($user['role']) {
            'admin' => 'admin',
            'lojista' => 'lojista',
            default => '',
        });
    }

    public function showRegister(): void
    {
        $this->render('auth/register', ['title' => 'Criar conta']);
    }

    public function register(): void
    {
        Csrf::validate($_POST['_token'] ?? null);
        $data = array_map('trim', $_POST);
        $errors = Validator::required($data, ['nome', 'email', 'senha', 'role']);

        if (!Validator::email($data['email'] ?? null)) {
            $errors['email'] = 'Email invalido.';
        }

        if (!in_array($data['role'], ['consumidor', 'lojista'], true)) {
            $errors['role'] = 'Perfil invalido.';
        }

        $userModel = new User();
        if ($userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Email ja cadastrado.';
        }

        if ($errors) {
            Session::flash('error', implode(' ', array_values($errors)));
            $this->redirect('cadastro');
        }

        $userModel->create($data + ['status' => 'ativo']);
        Session::flash('success', 'Conta criada. Faça login para continuar.');
        $this->redirect('login');
    }

    public function logout(): void
    {
        Csrf::validate($_POST['_token'] ?? null);
        Auth::logout();
        Session::flash('success', 'Sessao encerrada.');
        $this->redirect('');
    }
}
