<?php
class ChatbotController extends Controller
{
    public function ask(): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        $message = $this->extractMessage();
        $response = (new ChatbotFaq())->reply($message);

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    private function extractMessage(): string
    {
        $contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? ''));
        if (str_contains($contentType, 'application/json')) {
            $payload = json_decode((string) file_get_contents('php://input'), true);
            return trim((string) ($payload['message'] ?? ''));
        }

        return trim((string) ($_POST['message'] ?? ($_GET['message'] ?? '')));
    }
}
