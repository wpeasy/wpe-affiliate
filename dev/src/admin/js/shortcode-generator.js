const template = '[wpe_affiliate affiliate_id={affID} banner="{banner_name}"]'

export function generate (affID, banner_name){
        let tpl = template
        let retStr = tpl.replace('{affID}', affID).replace('{banner_name}', banner_name )
        return retStr
}