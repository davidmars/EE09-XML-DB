/**
 * Represents a GinetteRecord object.
 * @param {jQuery} jq
 * @return {GinetteRecord}
 * @constructor
 */
var GinetteRecord=function(jq){
    "use strict";
    var me=this;
    var jq=this.jq=$(jq);
    if(jq.data("GinetteRecord")){
        return jq.data("GinetteRecord");
    }
    jq.data("GinetteRecord",this);


    var saveBtn=jq.find("[ginette-record-save]")
    /**
     * Define if the record has been modified or not
     * @param {Boolean} state
     */
    this.setAsModified=function(state){
        if(state){
            saveBtn.addClass("blink");
        }else{
            saveBtn.removeClass("blink");
        }
    }

}
/**
 * Return the closest parent GinetteFieldFile object
 * @param jq
 * @return {GinetteFieldFile}
 */
GinetteRecord.getParent=function(jq){
    "use strict";
    return new GinetteRecord($(jq).closest("[ginette-record]"));

}
