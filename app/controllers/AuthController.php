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

        if ($user['role'] === 'admin') {
            $this->redirect('admin');
        }

        $store = (new Store())->byUser((int) $user['id']);
        $this->redirect($store ? 'lojista' : '');
    }

    public function showRegister(): void
    {
        $this->render('auth/register', ['title' => 'Criar conta']);
    }

    public function register(): void
    {
        Csrf::validate($_POST['_token'] ?? null);
        $data = array_map('trim', $_POST);
        $errors = Validator::required($data, ['nome', 'email', 'senha']);

        if (!Validator::email($data['email'] ?? null)) {
            $errors['email'] = 'Email invalido.';
        }

        $userModel = new User();
        if ($userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Email ja cadastrado.';
        }

        if ($errors) {
            Session::flash('error', implode(' ', array_values($errors)));
            $this->redirect('cadastro');
        }

        $userModel->create($data + ['role' => 'consumidor', 'status' => 'ativo']);
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
