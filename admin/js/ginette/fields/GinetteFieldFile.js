var GinetteFieldFile=function(jq){
    "use strict";
    var me=this;
    var jq=this.jq=$(jq);
    if(jq.data("GinetteFieldFile")){
        return jq.data("GinetteFieldFile");
    }
    jq.data("GinetteFieldFile",this);

    /**
     *
     * @type {jQuery}
     */
    var img=jq.find("img");
    if(img){
        img.on("load",function(e){
            jq.fadeTo(500,1);
        })
    }


    /**
     * Update the field with the given value
     * @param {String} fileId The file url
     */
    this.setValue=function(fileId){
        jq.fadeTo(500,0.5);
        jq.find("input[type='text']").val(fileId);
        img.attr("src",GinetteApiFileImage.imgShowAll(fileId,"300","300","000000","jpg"));
        var record=GinetteRecord.getParent(jq);
        record.setAsModified(true);
    }





}
/**
 * Return the closest parent GinetteFieldFile object
 * @param jq
 * @return {GinetteFieldFile}
 */
GinetteFieldFile.getParent=function(jq){
    "use strict";
    return new GinetteFieldFile($(jq).closest("[ginette-field-file]"));

}
