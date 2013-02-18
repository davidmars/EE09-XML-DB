var FileManager={
    /**
     * @type {PopIn}
     */
    popIn:null,
    /**
     * show the pop in
     */
    open:function(){
        "use strict";
        if(!FileManager.popIn){
            FileManager.popIn=PopInManager.loadPopIn("FileManager","/ginette/admin?p=files&action=popIn")
        }
        FileManager.popIn.show();
    },
    /**
     * hide the pop in
     */
    close:function(){
        "use strict";
        FileManager.popIn.hide();
    },
    onSelectItem:function(fileId){
        "use strict";

    }

}

//-------------------events on an item----------------------------------


Jaquy.body.on("click","[file-manager-item]",function(e){
    "use strict";
    e.preventDefault;
    FileManager.onSelectItem($(this).attr("file-manager-item"));
})

//--------------------events outside the pop in--------------------------

/**
 * A button to import a file
 * [file-manager-action="import-image"]
 */
Jaquy.body.on("click",'[file-manager-action="import-image"]',function(e){
    "use strict";
    var field=GinetteFieldFile.getParent($(this));

    FileManager.open();
    FileManager.onSelectItem=function(fileId){
        FileManager.close();
        //alert("You just select the file "+fileId);
        field.setValue(fileId);
    }
})
