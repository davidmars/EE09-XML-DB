var Jacky={}


Jacky.Loader = function(url, target) {
    this.events = new EventDispatcher();
    var me = this;

    this.start=function(){
        //me.events.dispatchEvent("startLoading");
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            success:
                function (response){
                    target.html(response);
                    me.events.dispatchEvent("loaded");
                }
        });
    }
}