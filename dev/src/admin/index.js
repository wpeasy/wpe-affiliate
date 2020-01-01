
import './scss/style.scss'
import WHMCS_CustomApi from './services/api'

import Feedback from './js/feedback.js'
import linkGenerator from "./js/link-generator";
import shortcodeGenerator from "./js/shortcode-generator";



const Api = new WHMCS_CustomApi(
    'https://store.wpeasy.net/wpeasy_manager/api/v1/',
    'zn1p0qqfhBzhfWtOQARbBLCPVtqqpf'
)
window.wpeAffiliate = {
    Api: Api,
    linkGenerator: linkGenerator,
    shortcodeGenerator: shortcodeGenerator
};


