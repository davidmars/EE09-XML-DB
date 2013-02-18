var PopIn;
PopIn = function (jq) {
    "use strict";
    /**
     *
     * @type {PopIn}
     */
    var me = this;
    /**
     *
     * @type {*|jQuery|HTMLElement}
     */
    var jq = this.jq = $(jq);



    /**
     * Show the pop in
     */
    this.show=function(){
        me.jq.css("display","block");
        me.jq.css("z-index",PopInManager.zindex++);
    }
    /**
     * Hide the pop in
     */
    this.hide=function(){
        me.jq.css("display","none");
    }



    //----------------------controllers--------------------------



    /**
     * Background close popin on click
     * @type {jQuery}
     */
    var bg=jq.find(">.bg");
    bg.on("click",function(e){
        me.hide();
    })
    /**
     * hide pop in markup
     * [data-dismiss='modal']
     */
    jq.on("click","[data-dismiss='modal']",function(e){
        e.preventDefault();
        me.hide();
    })

};
















/**
 *
 * @type {Object}
 */
var PopInManager={
    /**
     * The current pop in z-index that will be increased on popin show actions
     */
    zindex:5000,
    /**
     * List of PopIn
     */
    all:{},
    /**
     *
     * @param {String} popInName
     * @return {PopIn} The pop in or null
     */
    getPopIn:function(popInName){
        "use strict";
        if(PopInManager.all[popInName]){
            return PopInManager.all[popInName]
        }else{
            return null;
        }
    },
    /**
     *
     * @param {String} name
     * @param {String} url
     * @return {PopIn}
     */
    loadPopIn:function(name,url){
        "use strict";
        var popIn=PopInManager.getPopIn(name)
        if(popIn){
            return popIn;
        }else{
            //create a new pop in and index it
            var el=$("<div class='pop-in' pop-in='"+name+"'><div class='bg'></div><div class='pop-in-container'></div></div>");
            popIn=new PopIn(el);
            PopInManager.all[name]=popIn;
            Jaquy.body.append(el);
            var container=$(el.find(">.pop-in-container"));

            var loader=new SimpleAjax(url);
            loader.events.addEventListener(EVENT_AJAX_SUCCESS,function(content){
                container.html(content);
            });
            loader.send();

            return popIn;
        }
    }

}

