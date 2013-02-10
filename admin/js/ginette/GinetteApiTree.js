"use strict";
/**
 *
 * @type {Object}
 */
var GinetteApiTree={
    event:new EventDispatcher()
}
/**
 * Move a branch from one place to an other
 * @param {string} tree The tree id
 * @param {string} branchId The branch id to move from a place to an other place
 * @param {string} branchTarget The branch id where to put the branch
 * @param {string} position Position in the list where to place the branch...so it is sortable now
 */
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
        //console.log(e);
        GinetteApiTree.event.dispatchEvent(GinetteEvents.MOVE_BRANCH_SUCCESS,e);
    })
    loader.send();


}