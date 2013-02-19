var GinetteFieldRecord=function(jq){
    "use strict";
    var me=this;
    var jq=this.jq=$(jq);
    if(jq.data("GinetteFieldRecord")){
        return jq.data("GinetteFieldRecord");
    }
    jq.data("GinetteFieldRecord",this);

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
     * @param {String} recordId The record id
     */
    this.setValue=function(recordId){
        jq.fadeTo(500,0.5);
        /*
        jq.find("input[type='text']").val(fileId);
        img.attr("src",GinetteApiFileImage.imgShowAll(fileId,"300","300","000000","jpg"));
        var record=GinetteRecord.getParent(jq);
        record.setAsModified(true);
        */
    }





}
/**
 * Return the closest parent GinetteFieldRecord object
 * @param jq
 * @return {GinetteFieldRecord}
 */
GinetteFieldRecord.getParent=function(jq){
    "use strict";
    return new GinetteFieldRecord($(jq).closest("[ginette-field-record]"));

}
