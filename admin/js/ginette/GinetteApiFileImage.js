/**
 * Created with JetBrains PhpStorm.
 * User: heeek
 * Date: 18/02/13
 * Time: 07:13
 * To change this template use File | Settings | File Templates.
 */
var GinetteApiFileImage={
    /**
     * Returns the url of a "show all image"
     * @param {String} fileId Id of the image, so its relatvie path
     * @param {String} w Width of the image
     * @param {String} h Height of the image
     * @param {String} bgColor something like ff0000 for red
     * @param {String} mime something like "jpg" or "png"
     * @return {String} Url of the image
     */
    imgShowAll:function(fileId,w,h,bgColor,mime){
    "use strict";
        return GinetteDb.paths.cache+"/img/"+fileId+"/sizedShowAll-"+w+"-"+h+"-"+bgColor+"-95."+mime;
    }
}

