/**
 * Console log about module
 * Displays fancy information in browser console
 */
export default function consoleLogAbout() {
    const styles = [
        'color: #743f84',
        'font-size: 14px',
        'font-weight: bold',
        'text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.3)',
        'padding: 10px',
    ].join(';');

    console.log('%c=================================', styles);
    console.log('%c👋 Üdvözlünk az ERAWAN oldalán!', styles);
    console.log('%c=================================', styles);

    console.group('Névjegy');
    console.log('🔧 Verzió:', themeData?.version || 'No theme data');
    console.log('📦 Sablon neve:', themeData?.name || 'No theme name');
    themeData?.author && console.log('👤 Author:', themeData?.author || 'No author');
    console.log('🚀 Környezet: ', process.env.NODE_ENV || 'development');
    console.groupEnd();

    console.log('%c=================================', styles);
}
