export default (callback, wait) => {
    let timeout = null;

    return (...args) => {
        const next = () => callback(...args);
        clearTimeout(timeout);
        timeout = setTimeout(next, wait);
    };
}
