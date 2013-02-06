var EventDispatcher;
EventDispatcher = function () {
    var me = this;
    /**
     * contains the functions to call
     */
    var listeners = {};

    /**
     * dipatch an event
     * @param event String
     * @param params
     */
    this.dispatchEvent = function (event, params) {
        var listenersList = listeners[event];
        if (!listenersList) {
            //console.log("there is no event listeneres for " + event);
            return;
        }
        //call the registerd events
        for (var i = 0; i < listenersList.length; i++) {
            listenersList[i].fn(params);
        }
    }

    /**
     * add a event listener. when this event is fired (via dispatchEvent), the function will be launched.
     * @param event String
     * @param fn Function the function to call
     */
    this.addEventListener = function (event, fn) {
        var listenersList = listeners[event];
        if (!listenersList) {
            //init the object
            listenersList = listeners[event] = [];
        }
        listenersList.push(
            {
                event:event,
                fn:fn
            }
        );
    };

    /**
     * Remove event
     * @param event String
     */
    this.removeEventListener = function(event) {
        delete listeners[event];
    }

    this.addEventsListener = function (events, fn) {
        for (var i = 0; i < events.length; i++) {
            me.addEventListener(events[i], fn);
        }
    }
};


var EVENT_SUCCESS="event-success";
var EVENT_ERROR="event-error";
var EVENT_LOADING="event-loading";
var EVENT_RESIZE="event-resize";
var EVENT_CHANGE="event-change";
