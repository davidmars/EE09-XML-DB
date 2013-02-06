
function onDragOver(ev,el)
{
    ev.preventDefault();
    var drop=Drag.getParent(el);
    drop.setCurrentDrop(true,ev.offsetX,ev.offsetY);
}
function onDragLeave(ev,el){
    /*ev.preventDefault();
    var drop=Drag.getParent(el);
    drop.setCurrentDrop(false);*/
}
function onDrop(ev,el)
{
    /*ev.preventDefault();
    //var drop=Drag.getParent(el);
    Drag.current.drop();*/
}


function onDragStart(ev,el)
{
    Drag.getParent(el).setDragging(true);
}
function onDragEnd(ev,el)
{
    Drag.current.drop();
}


var Drag=function(jq){

    var jq=this.jq=$(jq);
    var me=this;
    this.ul=jq.find(">ul");
    this.label=jq.find(">label");
    this.checkbox=jq.find(">input");

    this.open=function(){
        me.checkbox.attr("checked","checked");
    }
    this.setDragging=function(state){
        if(state){
            me.jq.css("opacity",0.2);
            Drag.current=me;
            /*
            Drag.preview.html(me.jq.html());
            Drag.preview.find("ul").remove();
            Drag.preview.find("label").removeAttr("draggable");
            Drag.preview.find("label").removeAttr("ondragstart");
            Drag.preview.find("label").removeAttr("ondragend");
            Drag.preview.find("label").removeAttr("ondrop");
            Drag.preview.find("label").removeAttr("ondragover");
            Drag.preview.find("label").removeAttr("ondragleave");
            Drag.preview.css("opacity",0.5);
            */
        }else{
            me.jq.css("opacity",1);
        }
    }
    this.howToDrop=function(x,y){
        if(x<30){
            return "inside";
        }else{
            if(y<10){
                return "before";
            }else{
                return "after";
            }
        }
    }
    this.setCurrentDrop=function(state,x,y){
        if(state){
            if($.contains(Drag.current.jq[0],this.jq[0] )){
                //not dropable
                //Drag.currentDrop=null;

            }else{
                Drag.currentDrop=me;
                switch(me.howToDrop(x,y)){
                    case "inside":
                        me.open();
                        me.ul.prepend(Drag.preview);
                        break;
                    case "before":
                        me.jq.before(Drag.preview);
                        break;
                    case "after":
                        me.jq.after(Drag.preview);
                        break;
                }


            }
        }else{
            me.ul.css("border","");
            me.label.css("border","");
        }
    }
    this.drop=function(){
        Drag.preview.before(Drag.current.jq); //inject the node in place of the preview
        Drag.current.jq.fadeTo(0,0);
        Drag.current.jq.fadeTo("slow",1);
        Drag.preview.remove(); // remove the preview
        var branch=Drag.current.checkbox.attr("id");
        var branchContainer=Drag.getParent(Drag.current.jq.parent());
        var container=branchContainer.checkbox.attr("id");
        var list=Drag.current.jq.parent().children();
        for(var i=0;i<list.length;i++){
            if(list[i]==Drag.current.jq[0]){
                pos=i;
            }
        }
        console.log("put "+branch+" in "+container+" at position "+pos);
        GinetteApiTree.event.addEventListener("MOVE_BRANCH_SUCCESS",function(e){
            branchContainer.jq.replaceWith($(e.data));
        })
        branchContainer.jq.css("opacity",0.1);
        GinetteApiTree.moveBranch("main",branch,container,pos);
    }



}
Drag.preview=$("<li>-------------------</li>");
/**
 *
 * @param el
 * @return {Drag}
 */
Drag.getParent=function(el){
    return new Drag($(el).closest(".js-branch"));
}
/**
 * The currntly draged element
 */
Drag.current;
/**
 * The last droppable DragObject
 */
Drag.currentDrop;

var GinetteApiTree={
    event:new EventDispatcher()
}

GinetteApiTree.moveBranch=function(tree,branchId,branchTarget,position){
    var vars={
        p:"tree",
        action:"moveBranch",
        tree:"main",
        branchId:branchId,
        targetBranchId :branchTarget,
        position:position
    }

    var loader=new SimpleAjax("index.php",vars);
    loader.type="GET";
    loader.events.addEventListener(EVENT_AJAX_SUCCESS,function(e){
        console.log(e);
        GinetteApiTree.event.dispatchEvent("MOVE_BRANCH_SUCCESS",e);
    })
    loader.send();


}
