define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSlector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#batteryEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .next-btn":"onnext",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"batteryInfo:get",function(data){
                        _this.data = data;
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"battery:create batteryInfo:update",function(){
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "batteryInfo");
                    });
                    _this.listenTo(Backbone.Events,"battery:create:next",function(){
                        var data = _this.getParam();
                        _this.oncancel();
                        Backbone.Events.trigger("battery:next", {
                            sid:data.sid,
                            site_name:data.site_name
                        });
                    });
                },
                setValue:function(data){
                    var data = data || this.data;
                    if(data){
                        common.setFormValue(this.el,data);
                    }
                },
                getParam:function(){
                    return common.getFormValue(this.el,true);
                },
                showErrTips:function(tips){
                    alert(tips);
                    return false;
                },
                validate:function(param){
                    if(!param.site_name){
                        return this.showErrTips('站点为必填项');
                    }
                    if(!param.sid){
                        return this.showErrTips('站点不存在');
                    }
                    var isvalidate = true;
                    var $mastFills = $(".mast-fill",this.el);
                    $mastFills.each(function(i,mf){
                        var key = $(mf).attr("for"),title;
                        if(key && (typeof param[key] == "undefined" || !param[key])){
                            title = $(mf).parent().html().replace(/<i[^>]*>.*(?=<\/i>)<\/i>/gi,'');
                            alert(title+"为必填项");
                            isvalidate = false;
                            return false;
                        }
                    })
                    if(!isvalidate){return false;}
                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();

                    if(_this.validate(_param)){
                        if(_param.id){
                            API.updateBatteryInfo(_param);
                        }else{
                            API.createBattery(_param);
                        }
                    }
                },
                onnext:function(){
                    var _this = this,
                        _param = _this.getParam();

                    if(_this.validate(_param)){
                        API.createBattery(_param,"battery:create:next");
                    }
                },
                oncancel:function(){
                    this.stopListening();
                    this.dialogObj.dialog( "destroy" );
                    $(".ui-dialog,.ui-widget-overlay").remove();
                    stationList.autocomplete('destroy');
                }
            }
        },
        stationList;
    return {
        show:function(id,data){
            var $dialogWrap = $("#batteryEditTpl-dialog").length?$("#batteryEditTpl-dialog").replaceWith($($("#batteryEditTpl").html())):$($("#batteryEditTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:560,
                width:1000,
                title:"电池信息",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    if(!id && data){
                        $("[key=site_name]").attr('disabled','disabled');
                    }

                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        $(".submit-btn",view.el).show();
                        API.getBatteryInfo({id:id});
                    }else{
                        $(".next-btn",view.el).show();
                    }

                    if(data){
                        view.setValue(data);
                    }

                    stationList = stationSlector.init({
                        extOption:{
                            select:function(event, ui){
                                $(this).val(ui.item.label);
                                $("[key=sid]").val(ui.item.value);
                                return false;
                            }
                        }
                    });

                    //日期选择器
                    $( "form.jqtransform [ipttype=date]" ).datepicker({
                        dateFormat: "yy-mm-dd"
                    });
                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})