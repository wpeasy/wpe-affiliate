const axios = require('axios');

export default class Api {

    constructor(baseEndpoint, apiKey) {

        this.axiosInstance = axios.create({
            baseURL: baseEndpoint,
            withCredentials: false,
            headers: {
                common: {Authorization: apiKey}
            }
        })
    }

    /*
    Generic Api Call method
     */
    doCall(action, paramObject) {
        return new Promise((resolve, reject) => {
                //alert('TEST')
                this.axiosInstance.post(action, paramObject)
                    .then(function (response) {
                            resolve(response.data)
                        }
                    ).catch(function (error) {
                        let response

                        if (typeof error.response === 'undefined') {
                            response = {
                                data: {
                                    message: 'A network error occurred. This could be a CORS issue or a dropped internet connection. It is not possible for us to know.',
                                    status: 'warning'
                                }
                            };
                        } else {
                            response = error.response
                        }
                        reject(response)
                    }
                )
            }
        )
    }
}