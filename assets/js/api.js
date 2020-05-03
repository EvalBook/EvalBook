
let Api = {
    // The base url to call in order to consume api.
    baseUrl: 'https://127.0.0.1:8000',

    query: async function(route, body, callback) {

        const response = await fetch(`${this.baseUrl}${route}`, {
            method: 'POST',
            credentials: 'include',
            body: JSON.stringify(body),
        })

        let data = await response.json();
        if(callback)
            callback.action(callback.param);
        return data;

    }
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
            return await Api.query('/api/strings',{
                domain: domain,
                strings: idArrayKeys,
            });
        }
    }
};

export { Language };
export { Api }