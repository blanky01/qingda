define(['require','api','backbone','context','common','zTreeExcheck'],function(require,API,Backbone,context,common){
    var zTree,
        overFlag = false,
        setting = {
            check: {
                enable: true
            },
            data: {
                key:{
                    name:'title'
                },
                simpleData: {
                    enable: true,
                    pIdKey: 'pid'
                }
            },
            callback:{
                onCheck:function(){
                    overFlag=false;
                    context.setCurStations(navView.getCheckedData());
                }
            }
        },
        navView;
    var nav_extobj = {
        el:$("#nav"),
        name:"nav",
        data:null,
        tree:null,
        navPlugin:null,
        initialize:function(data){
            console.log('init')
            var _this = this;
            _this.listenTo(Backbone.Events,"nav:update",function(data){
                _this.data = data.list;
                _this.filterData().render();
                overFlag = true;
            });
        },
        /*selectFirst:function(){
            if(this.tree){
                var nodes = this.tree.getNodes();
                if(nodes.length){
                    this.tree.checkNode(nodes[0],true,true,true);
                }
            }
        },*/
        filterData:function(){
            return this;
        },
        getCheckedData:function(){
            var checkedNodes = this.tree.getCheckedNodes(),
                checkedData = {};
            $.each(checkedNodes,function(i,node){
                checkedData[node.id] = {
                    id:node.id,
                    pId:node.pId,
                    name:node.name,
                    level:node.level
                }
            })
            return checkedData;
        },
        render:function(){
            $.fn.zTree.init($("#nav"), setting, this.data);
            this.tree = $.fn.zTree.getZTreeObj('nav');
            this.tree.expandAll(true);
            if(this.ids){
                if(this.ids.sid){
                    var nodes = this.tree.getNodes();
                    for (var i=0, l=nodes.length; i < l; i++) {
                        if(nodes[i].leveltype === 1){
                            var childrens = nodes[i].children;
                            for(var j = 0 ; j < childrens.length ;j++){
                                var subChildren = childrens[j].children;
                                if(!subChildren){
                                    continue;
                                }
                                for(var k = 0 ; k < subChildren.length ; k++){
                                    if(subChildren[k].id === this.ids.sid)
                                        this.tree.checkNode(subChildren[i], true, true); 
                                }
                            }
                           
                        }
                        
                    }
                }
            }else{
                this.tree.checkAllNodes(true);
            }
            return this;
        }
    }

    return {
        init:function(sys,listType,sub,ids){
            if(!navView){
                navView = new (Backbone.View.extend($.extend(true,{},nav_extobj,{ids:ids})))();
            }
            return this;
        },
        run:function(cb){
            API.getNavData(cb);
        },
        isOver:function(value){
            if(typeof value == 'undefined'){
                return !!overFlag;
            }else{
                overFlag = !!value;
            }
        },
        getSites:function(){
            var ids={ids:[],map:{}, pids: []},selectedNode;
            if(navView.tree){
                selectedNode = navView.tree.getCheckedNodes();
                $.each(selectedNode,function(i,node){
                    if(node.leveltype == "2"){
                        ids.ids.push(node.id);
                        ids.pids.push(node.pid);
                        ids.map[node.id] = node;
                    }
                })
            }
            return ids;
        },
        getSelectedNodePid: function(){
            var getSelectedNodeId = [];
            if(navView.tree){
                var nodes = navView.tree.getSelectedNodes();
                $.each(nodes, function(i,node){
                    getSelectedNodeId.push(node.pid);
                })
                
            }
            return getSelectedNodeId;
        },
        getSelectedNodeId: function(){
            var getSelectedNodeId = -1;
            if(navView.tree){
                var nodes = navView.tree.getSelectedNodes();
                $.each(nodes, function(i,node){
                    getSelectedNodeId = node.id;
                })
                
            }
            return getSelectedNodeId
        },
        getGroups:function(){
            var ids={ids:[],map:{}},selectedNode;
            if(navView.tree){
                selectedNode = navView.tree.getCheckedNodes();
                $.each(selectedNode,function(i,node){
                    if(node.leveltype == "3"){
                        ids.ids.push(node.id);
                        ids.map[node.id] = node;
                    }
                })
            }
            return ids;
        },
        getBatterys:function(siteid){
            var ids={ids:[],map:{}},groups,groupids=[],selectedNode;

            if(siteid){
                groups = this.getGroups();
                $.each(groups.ids,function(i,id){
                    if(groups.map[id].pid == siteid){
                        groupids.push(id);
                    }
                })
            }

            if(navView.tree){
                selectedNode = navView.tree.getCheckedNodes();
                $.each(selectedNode,function(i,node){
                    if(node.leveltype == "4"){
                        if(groupids && groupids.length && !common.inArray(node.pid,groupids)){
                            return;
                        }
                        ids.ids.push(node.id);
                        ids.map[node.id] = node;
                    }
                })
            }
            console.log('getBatterys', ids);
            return ids;
            console.log('getBatterys', ids);
        },
        getBatteryIds: function(){
            var ids={ids:[],map:{}},selectedNode;
            if(navView.tree){
                selectedNode = navView.tree.getCheckedNodes();
                $.each(selectedNode,function(i,node){
                    if(node.leveltype == "4"){
                        ids.ids.push(node.id);
                        ids.map[node.id] = node;
                    }
                })
            }
            return ids;
        }
    };
})