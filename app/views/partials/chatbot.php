<div class="chatbot-root" data-help-chatbot data-chatbot-endpoint="<?= e(url('chat/perguntar')) ?>">
    <button type="button" class="chatbot-toggle" data-chatbot-toggle aria-expanded="false" aria-controls="chatbot-panel">
        Ajuda
    </button>

    <section id="chatbot-panel" class="chatbot-panel hidden" data-chatbot-panel aria-live="polite">
        <header class="chatbot-header">
            <div>
                <p class="chatbot-title">Assistente de Cadastro</p>
                <p class="chatbot-subtitle">Tire duvidas em tempo real</p>
            </div>
            <button type="button" class="chatbot-close" data-chatbot-close aria-label="Fechar assistente">x</button>
        </header>

        <div class="chatbot-messages" data-chatbot-messages></div>

        <div class="chatbot-options" data-chatbot-options></div>

        <form class="chatbot-form" data-chatbot-form>
            <input type="text" class="chatbot-input" data-chatbot-input name="message" maxlength="200" placeholder="Ex.: Como criar cadastro?" autocomplete="off">
            <button type="submit" class="chatbot-send" data-chatbot-send>Enviar</button>
        </form>
    </section>
</div>
