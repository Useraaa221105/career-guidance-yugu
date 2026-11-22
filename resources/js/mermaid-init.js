import mermaid from 'mermaid';

mermaid.initialize({
    startOnLoad: false,
    theme: 'base',
    themeVariables: {
        primaryColor:       '#e0e7ff',
        primaryTextColor:   '#1e1b4b',
        primaryBorderColor: '#6366f1',
        lineColor:          '#94a3b8',
        fontSize:           '13px',
    },
    flowchart: {
        curve:       'basis',
        padding:     20,
        nodeSpacing: 50,
        rankSpacing: 80,
    },
    securityLevel: 'loose',
});

// DOMContentLoaded уже мог сработать к моменту загрузки скрипта,
// поэтому запускаем вручную
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => mermaid.run());
} else {
    mermaid.run();
}
