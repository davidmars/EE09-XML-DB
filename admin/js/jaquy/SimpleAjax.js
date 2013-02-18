/**
 * User: david
 * Date: 08/12/12
 * Time: 05:10
 * A simple class to manage Ajax requests
 */

/**
 *
 *
 * @param url {String} The url to load.
 * @param data {Object} The data to send.
 * @param cache {Boolean} Set it to true to use cached requests
 * @constructor
 */
var SimpleAjax=function(url,data,cache){
    "use strict";

    var me = this;

    var url=url;
    var data=data;

    /**
     * The type of request to make ("POST" or "GET"), default is "POST".
     * @type {String}
     */
    this.type = "POST";

    /**
     * (xml, json, script, or html)
     * @type {String}
     */
    this.dataType = null;

    /**
     * Here you can listen to EVENT_AJAX_SUCCESS that will return a AjaxEventSuccess object.
     * @type {EventDispatcher}
     */
    this.events=new EventDispatcher();
    /**
     * Send the stuff
     */
    this.send=function(){
        $.ajax({
            type : me.type,
            url: url,
            data: data,
            cache:cache,
            dataType : me.dataType,
            success:
                function (response){
                    me.events.dispatchEvent(EVENT_AJAX_SUCCESS,response);
                }
        });
    }
}
/**
 * The event identifier for a successful ajax request.
 * @type {String}
 */
var EVENT_AJAX_SUCCESS="event-ajax-success";

