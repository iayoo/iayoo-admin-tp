layui.define(['jquery', 'layer','loading'], function (exports) {
    "use strict";

    const $ = layui.jquery;
    const layer = layui.layer;
    const loading = layui.loading;

    const request = {};
    request.post = function (options) {
        loading.Load(1)
        if (undefined === options.type){
            options.type = "POST";
            console.log(options)
        }
        $.ajax({
            url:options.url,
            type:"POST",
            dataType:"json",
            success: function(res) {
                loading.loadRemove();
                if (undefined!==res.code && res.code === 0 && undefined !== options.success){
                    options.success(res);
                }else{
                    if (undefined !== options.error){
                        options.error(res);
                    }
                }
            },
            error:function (res) {
                loading.loadRemove();
                if (undefined !== res.responseJSON && undefined !== res.responseJSON.message){
                    layer.msg(res.responseJSON.message, {
                        icon: 2,
                        time: 1000
                    });
                }else{
                    layer.msg("网络异常", {
                        icon: 2,
                        time: 1000
                    });
                }

                if (undefined !== options.error){
                    options.error(res);
                }
            },
            complete:function (res) {
                loading.loadRemove();
            }
        })
    }



    exports('request', request);
});