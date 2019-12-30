import './scss/style.scss'
import validator from 'validator';
import VanillaModal from 'vanilla-modal'
import WHMCS_CustomApi from './services/api'

import Feedback from './js/feedback.js'
import {generate as linkGenerator} from "./js/link-generator";
import {generate as shortcodeGenerator} from "./js/shortcode-generator";



const Api = new WHMCS_CustomApi(
    'https://store.wpeasy.net/wpeasy_manager/api/v1/',
    'zn1p0qqfhBzhfWtOQARbBLCPVtqqpf'
)

let $getIdButon, $emailField, $affiliateIdField, feedbackInstance, $linksContainer, $generatedLinkContainer, $generatedShortcodeContainer

(function ($) {
    $(document).ready(() => {
        const modal = new VanillaModal()

        $linksContainer = $('#wpe-admin-sc-container')
        $generatedLinkContainer = $('#generatedLink')
        $generatedShortcodeContainer = $('#generatedShortcode')
        $getIdButon = $('#getAffiliateID')
        $emailField = $('#whmcs_email')
        $affiliateIdField = $('#affiliate_id')
        feedbackInstance = new Feedback($('#wpe-feedback-container'))
        feedbackInstance.hideFeedback()

        $getIdButon.on('click', e => {
            feedbackInstance.hideFeedback()
            Api.doCall(
                '/user/sendDetailsToUsersEmail',
                {email: $emailField.val()}
            )
                .then(response => {
                    //console.log('RESULT', response)
                    feedbackInstance.showFeedback(response.data.status, response.data.message)
                })
                .catch(response => {
                    let msg = response.data.message
                    let status = response.data.status
                    feedbackInstance.showFeedback(status, msg)
                })
        })

        $emailField.on('keyup', onChange)
        $affiliateIdField.on('keyup', onChange)

        $('.wpe-admin-ad-list .item').on('click', (e) => {
            let img = e.currentTarget.firstElementChild
            let value = linkGenerator(
                $affiliateIdField.val(),
                img.src,
                img.width,
                img.height
            )
            $generatedLinkContainer.find('#generatedLinkInput').val(value)
            $generatedLinkContainer.find('#generatedLinkText').text(value)
            $generatedLinkContainer.removeClass('copied')

            value = shortcodeGenerator(
                $affiliateIdField.val(),
                img.name
            )
            $generatedShortcodeContainer.find('#generatedShortcodeInput').val(value)
            $generatedShortcodeContainer.find('#generatedShortcodeText').text(value)
            removeCopiedClass()
            modal.open('#scModal')
        })

        $generatedLinkContainer.on('click', (e)=>{
            removeCopiedClass()
            let el = document.getElementById('generatedLinkInput')
            el.focus();
            el.select();
            document.execCommand("copy");
            $generatedLinkContainer.addClass('copied')
        })

        $generatedShortcodeContainer.on('click', (e)=>{
            removeCopiedClass()
            let el = document.getElementById('generatedShortcodeInput')
            el.focus();
            el.select();
            document.execCommand("copy");
            $generatedShortcodeContainer.addClass('copied')
        })

        onChange()
    })

    function removeCopiedClass() {
        $('.modal .copied').removeClass('copied')
    }

    function onChange() {
        if (validateFields()) {
            $linksContainer.fadeIn()
        } else {
            $linksContainer.fadeOut()
        }
    }


    function validateIdField() {
        const id = $affiliateIdField.val()
        return validator.isNumeric(id);
    }

    function validateEmailField() {
        if (validator.isEmail($emailField.val())) {
            $getIdButon.prop('disabled', false);
            $affiliateIdField.prop('disabled', false)
            return true;
        } else {
            $getIdButon.prop('disabled', true);
            $affiliateIdField.prop('disabled', true)
            return false;
        }
    }

    function validateFields() {
        const emailOK = validateEmailField()
        const idOK = validateIdField()
        return emailOK && idOK
    }
})(jQuery)

