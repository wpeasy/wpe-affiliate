const template = '<a href="https://store.wpeasy.net/aff.php?aff={affID}"><img src="{src}" width="{width}" height="{height}" border="0"></a>'

export default function generate (affID, src, width, height){
        let tpl = template
        let retStr = tpl.replace('{affID}', affID).replace('{src}', src).replace('{width}',width).replace('{height}',height)
        return retStr
}