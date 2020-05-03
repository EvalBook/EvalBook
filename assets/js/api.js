
let api = {
    // The base url to call in order to consume api.
    baseUrl: 'https://127.0.0.1:8000/api',
};

/**
 * Handle translations api requests.
 * @type {{test: Language.test, getLocale: (function(): Promise<json>)}}
 */
let Language = {
    /**
     * Return a json translated key - values of passed object.
     * @returns {Promise<json>} | False
     */
    getStrings: async function(domain, idArrayKeys) {
        if(idArrayKeys && idArrayKeys.length > 0) {
            const response = await fetch(`${api.baseUrl}/strings`, {
                method: 'POST',
                credentials: 'include',
                body: JSON.stringify({
                    domain: domain,
                    strings: idArrayKeys
                })
            });

            return await response.json();
        }
    }
};

export { Language };