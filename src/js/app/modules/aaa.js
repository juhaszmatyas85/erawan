export function aaa() {
    console.log('aaa');

    const event = new Event('myCustomEvent');
    document.dispatchEvent(event);
}
