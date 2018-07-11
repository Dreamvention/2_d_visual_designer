module.exports = function(){
    var icons = require('./icons.json')
    var content = "window.iconset = window.iconset? window.iconset : {};window.iconset['fontawesome']=["
    delete icons[0]
    for (var key in icons) {
        var styles = icons[key].styles
        for(var styleKey in styles) {
            content +='"fa'+styles[styleKey].substring(0,1)+' fa-'+key+'",';
        }
    }
    content+="]"

    return {content: content}
}