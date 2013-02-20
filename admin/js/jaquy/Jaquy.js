"use strict";
var Jaquy;
Jaquy = {
    /**
     * {jQuery} The body element
     */
    body:$("body"),
    init:function(){
        Jaquy.autoLoadAll(Jaquy.body);
    }



};

/*-----------------------------------------------ajax auto load---------------------------*/

/**
 * Call it to auto load the [jaquy-autoload] elements
 */
Jaquy.autoLoadAll=function(jq){
    if (!jq) {
        jq = Jaquy.body;
    }
    var els=jq.find("[jaquy-autoload]");
    var al;
    for(var i=0;i<els.length;i++){
        al=new Jaquy.AutoLoader($(els[i]));
    }
}
Jaquy.AutoLoader=function(jq){
    var me=this;
    var jq=$(jq);
    var url=jq.attr("jaquy-autoload");
    //remove the attr to no re process the stuff
    jq.removeAttr("jaquy-autoload");
    jq.attr("jaquy-autoload-loading",url);
    var req=new SimpleAjax(url);
    var listener=req.events.addEventListener(EVENT_AJAX_SUCCESS,function(content){
        jq.html(content);
        jq.removeAttr("jaquy-autoload-loading");
        req.events.removeEventListener(listener);
        //recursive autoload
        Jaquy.autoLoadAll(jq);
    })
    req.send();
}

/*-----------------------------------------------ajax load somewhere on click---------------------------*/

/**
 * Loads ajax in a specific target
 * dependencies : SimpleAjax
 * Markup :
 * <a href="toto.html" load-in-jaquy-ajax-receiver="my-target">toto</a>
 * <a href="titi.html" load-in-jaquy-ajax-receiver="my-target">titi</a>
 * <div jaquy-ajax-receiver='my-target'>Here will be loaded ajax content...</div>
 */
Jaquy.body.on("click","[load-in-jaquy-ajax-receiver]",function(e){
    e.preventDefault();
    var targetId=$(this).attr("load-in-jaquy-ajax-receiver");
    var url=$(this).attr("href");
    var target=Jaquy.body.find("[jaquy-ajax-receiver='"+targetId+"']");
    target.html("loading... "+url);
    var loader=new SimpleAjax(url,{});
    loader.events.addEventListener(EVENT_AJAX_SUCCESS,function(content){
        target.html(content);
        Jaquy.autoLoadAll(target);
    })
    loader.send();
})

/*-----------------------------------------------css .active on click---------------------------*/

/**
 * Put a .active css class on the element itself on click
 *
 * markup : [jacky-activable-item]
 * markup to get only one active element in children list : [jacky-activable-item-list]
 * <ul jacky-activable-item-list >
 *     <li jacky-activable-item></li>
 *     <li jacky-activable-item class="active">you just clicked me baby</li>
 *     <li jacky-activable-item></li>
 *     <li jacky-activable-item></li>
 *     <li jacky-activable-item></li>
 * </ul>
 */
Jaquy.body.on("click","[jacky-activable-item]",function(e){
    e.preventDefault();
    var parent=$(this).closest("[jacky-activable-item-list]");
    if(parent.length>0){
        parent.find("[jacky-activable-item]").removeClass("active");
    }
    $(this).addClass("active");
})



















$(document).ready(function() {
    Jaquy.init();
});






