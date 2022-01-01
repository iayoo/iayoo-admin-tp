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
        }
        let params = {};
        // 处理请求参数
        if (undefined !== options.data && 'object' === (typeof options.data)){
            params = JSON.stringify(options.data)
        }
        $.ajax({
            url:options.url,
            type:"POST",
            dataType:"json",
            data:params,
            contentType:'application/json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                loading.loadRemove();
                // 判断code是否为未登录code
                // 判断当前是否为iframe子页面
                // 触发上级页面跳转登录页面
                if (undefined !==res.code && res.code === 40100 && parent.location.href !== location.href){
                    layer.msg("登录已过期，正在跳转至登录页", {
                        icon: 2,
                        time: 1000
                    },function () {
                        parent.location.href = location.href;
                    });
                    return;
                }
                // code 为 0 时请求正常
                if (undefined!==res.code && res.code === 0){
                    if (undefined !== options.isClose && options.isClose){
                        parent.layer.close(parent.layer.getFrameIndex(window.name));//关闭当前页
                    }
                    if (undefined !== options.reloadTable && options.reloadTable){
                        // 刷新上级table
                        parent.layui.table.reload(options.reloadTable);
                    }
                    if (undefined !== res.message){
                        layer.msg(res.message, {
                            icon: 1,
                            time: 1000
                        },function () {
                            if (undefined !== options.success){
                                options.success(res);
                            }
                        });
                    }
                }else{
                    layer.msg(res.message, {
                        icon: 2,
                        time: 1000
                    },function () {
                        if (undefined !== options.error){
                            options.error(res);
                        }
                    });
                    return false;
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