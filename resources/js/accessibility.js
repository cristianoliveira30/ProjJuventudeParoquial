// resources/js/accessibility.js

class AccessibilityManagerService {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.isHighContrast = localStorage.getItem('high-contrast') === 'true';
    }

    init() {
        this.applyState();
    }

    setTheme(newTheme) {
        this.theme = newTheme;
        localStorage.setItem('theme', newTheme);
        localStorage.setItem('color-theme', newTheme); // Fallback Flowbite

        // Se o usuário mudar o tema enquanto o alto contraste estiver ativo,
        // nós desativamos o alto contraste para mostrar o novo tema.
        if (this.isHighContrast) {
            this.isHighContrast = false;
            localStorage.setItem('high-contrast', 'false');
        }

        this.applyState();
    }

    toggleHighContrast() {
        this.isHighContrast = !this.isHighContrast;
        localStorage.setItem('high-contrast', this.isHighContrast.toString());
        this.applyState();
    }

    applyState() {
        const html = document.documentElement;

        // 1. Limpeza rigorosa
        html.classList.remove('dark', 'black', 'hc');

        // 2. Avaliação de Estado
        if (this.isHighContrast) {
            // MODO ALTO CONTRASTE ATIVO:
            // Injeta 'dark' (para base do Flowbite) e 'hc' (nossa sobreposição estrita)
            html.classList.add('dark', 'hc');
        } else {
            // MODO NORMAL:
            // Restaura o último tema escolhido
            if (this.theme === 'dark') html.classList.add('dark');
            if (this.theme === 'black') html.classList.add('black');
            // Se for 'light', o Tailwind já assume como padrão sem classes adicionais.
        }
    }
}

// Instancia e expõe globalmente para o onclick do Blade
window.AccessibilityManager = new AccessibilityManagerService();
