export default (str) => {
    const parser = new DOMParser()
    const strConvertedToHTMLM    = parser.parseFromString(str, 'text/html')
    return strConvertedToHTMLM
}
