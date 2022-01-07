let formTpl =
    `{{#  layui.each(d, function(index, item){ }}

<div class="layui-form-item">
    <label class="layui-form-label" style="padding: 5px 15px;">{{ item.field }}</label>
<div >
{{#  layui.each(item.child, function(skuIndex, skuItem){ }}
<a class="layui-btn layui-btn-sm layui-btn-primary layui-border sku-item">
    <span class="sku-item-remove" onclick="handleRemoveSkuItem('{{ index+'-'+skuIndex }}')"> </span>
{{ skuItem.value }}
</a>
{{#  }); }}
<a class="layui-btn layui-btn-normal layui-btn-sm layui-border" onclick="handleAddSkuItem('{{ index }}')">
    <span class="icon pear-icon add-bold pear-icon-add-bold"> </span>
    </a>
    <a class="layui-btn layui-btn-normal layui-btn-sm layui-border" onclick="handleRemoveSkuItem('{{ index }}')">
    <span class="icon pear-icon ashbin pear-icon-ashbin"> </span>
    </a>
    </div>
    </div>
{{#  }); }}`;

layui.define(['jquery', 'layer','laytpl','table'], function (exports) {
    "use strict";

    const $ = layui.jquery;
    const layer = layui.layer;
    let laytpl = layui.laytpl;
    let table = layui.table;

    let skuData = [
        // {'field':'颜色',child:[{value:'红色'},{value:'蓝色'}]},
        // {'field':'内存',child:[{value:'128G'},{value:'256G'}]},
        // {'field':'套餐',child:[{value:'裸机'},{value:'官配'}]},
    ]

    /**
     * 笛卡尔积
     * @returns {any|*[]|U}
     */
    function descartes() {
        if (arguments[0].length < 2){
            if (arguments[0][0] === undefined){
                return [];
            }
            let res = [];
            let field = arguments[0][0].field;
            arguments[0][0].child.forEach(function (f) {
                res.push([{'field':field,'value':f.value}])
            })
            return res;
        }
        return arguments[0].reduce(function (pre,cur) {
            let c = [];
            let p = [];
            let next = [];
            if (cur.child !== undefined) {
                let curFiled = cur.field;
                cur.child.forEach(function (f) {
                    c.push({'field':curFiled,'value':f.value})
                })
            }
            if (pre.child !== undefined) {
                let preFiled = pre.field;
                pre.child.forEach(function (f) {
                    p.push({'field':preFiled,'value':f.value})
                })
            }else{
                p = pre;
            }

            if (c.length<=0){
                p.forEach(function (pItem) {
                    next.push([].concat(pItem))
                })
                return next;
            }
            if (p.length<=0){
                c.forEach(function (cItem) {
                    next.push([].concat(cItem))
                })
                return next;
            }

            p.forEach(function (pItem) {
                c.forEach(function (cItem) {
                    next.push([].concat(pItem,cItem))
                })
            })
            return next;
        })
    }

    /**
     *
     * @param colIndex:table中列索引
     * @param startIndex：合并单元格起始索引
     * @param endIndex：合并单元格结束索引
     * @param trArr：单列单元格元素集合
     * @param data：后端返回数据集合
     * @param colName：当前列字段名
     */
    function mergeSomeRows(colIndex,startIndex,endIndex,trArr,data,colName) {
        let mark = 1;
        for (let j = startIndex + 1; j < endIndex; j++) {
            ++mark;
            let tdCurArr = trArr.eq(j).find("td").eq(colIndex);//获取当前行的当前列
            let tdPreArr = trArr.eq(startIndex).find("td").eq(colIndex);//获取相同列的第一列
            if (data[j][colName] === data[j - 1][colName]) { //后一行的值与前一行的值做比较，相同就需要合并
                // console.log()
                //相同列的第一列增加rowspan属性
                tdPreArr.each(function () {
                    $(this).attr("rowspan", mark);
                });
                //当前行隐藏
                tdCurArr.each(function () {
                    $(this).css("display", "none");
                });
            } else {
                mark = 1;
                startIndex = j;
            }
        }
    }

    function merge(res,mergeField) {
        //初始化分割点
        let trArr = $(".layui-table-body>.layui-table").find("tr");//所有行
        let tdHeaderArr = $(".layui-table-header>.layui-table").find("th");//所有行
        let mergeIndexArr = [];

        //这里涉及到简单的运算，mark是计算每次需要合并的格子数
        let mark = 1;
        let index = 0;
        let mergeIndex = 0;
        mergeField.forEach(function (fieldName) {
            let col = 0;
            let curCol = 0;
            // console.log(tdHeaderArr)
            tdHeaderArr.each(function () {
                if ($(this).data('field') === fieldName){
                    curCol = col;
                }
                col++
            })
            if (res.data.length <= 0){
                return null;
            }
            // console.log(res.data);
            res.data.reduce(function (pre,cur) {
                let tdCurArr = trArr.eq(index+1).find("td").eq(curCol);//获取当前行的当前列
                if (pre[fieldName] === cur[fieldName]){
                    mark += 1;
                    tdCurArr.each(function () {
                        $(this).css("display", "none");
                    });
                }else{
                    if (mark>1){
                        mergeIndexArr.push({'mark':mark,index:mergeIndex,fieldName:fieldName,colIndex:curCol})
                    }
                    mark = 1;
                    mergeIndex = index+1;
                }
                index += 1;
                return cur;
            })
            if (mark>1){
                mergeIndexArr.push({'mark':mark,index:mergeIndex,fieldName:fieldName,colIndex:curCol})
            }
            mark = 1;
            index = 0;
            mergeIndex = index;
        })

        // console.log(mergeIndexArr)
        mergeIndexArr.forEach(function (merger) {
            let tdPreArr = trArr.eq(merger.index).find("td").eq(merger.colIndex);
            tdPreArr.each(function () {
                $(this).attr("rowspan",merger.mark);
            });
        })
    }

    const skuTable = {
        form:"",
        table:"",
        formElement:null
    };
    /**
     * 渲染SKU表单
     * @param form
     */
    skuTable.renderForm = function(form){
        if (form !== undefined){
            skuTable.form = form;
        }
        console.log("渲染SKU表单");
        // 通过data数据渲染script模板
        if (skuTable.formElement === undefined || skuTable.formElement === null){
            skuTable.formElemen = document.getElementById(skuTable.form);
        }
        laytpl(formTpl).render(skuData, function (html) {
            skuTable.formElemen.innerHTML = html;
        });
    }

    window.handleAddSkuItem = function (index){
        //默认prompt
        layer.prompt({title:'添加sku属性'},function(val, pageIndex){
            // 关闭弹窗
            layer.close(pageIndex);
            skuData[Number(index)].child.push({value:val,field:skuData[Number(index)].field});
            skuTable.renderForm();
            skuTable.render();
        });
    }
    window.handleAddSku = function (){
        //默认prompt
        layer.prompt({title:'添加规格'},function(val, index){
            // 关闭弹窗
            layer.close(index);
            skuData.push({child:[],field:val})
            skuTable.renderForm();
            skuTable.render();
        });
    }
    window.handleRemoveSkuItem = function (index){
        //默认prompt
        let indexArr = index.split('-')

        let skuName = skuData[indexArr[0]].field;
        if (indexArr[1] !== undefined){
            //询问框
            let skuItemName = skuData[indexArr[0]].child[indexArr[1]].value;
            layer.confirm('是否要删除'+ skuName +'-'+ skuItemName +'？', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                layer.close(index);
                skuData[indexArr[0]].child.splice(indexArr[1],1);
                skuTable.renderForm();
                skuTable.render();
            });
        }else{
            layer.confirm('是否要删除'+ skuName  +'？', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                layer.close(index);
                skuData.splice(indexArr[0],1);
                skuTable.renderForm();
                skuTable.render();
            });
        }
    }

    skuTable.render = function(tableEl){
        if (tableEl!==undefined) skuTable.table = tableEl;
        let tableColsOption = [];
        // console.log(skuData);
        let data = descartes(skuData);
        console.log("渲染 sku table");
        // console.log(data)
        let mergeField = []
        skuData.forEach(function (sku) {
            if (sku.child.length<=0){
                return;
            }
            tableColsOption.push({
                field:sku.field,
                title:sku.field,
                align:'center'
            })
            mergeField.push(sku.field)
        })
        tableColsOption.push(
            {field:'skuNum', width:200, title: 'sku码',templet: '#stockTpl', unresize: true,align:'center'}
            ,{field:'stock', width:200, title: '库存',templet: '#stockTpl', unresize: true,align:'center'}
            ,{field:'price', width:200, title: '价格',templet: '#stockTpl', unresize: true,align:'center'}
            ,{field:'price', width:200, title: '成本价',templet: '#stockTpl', unresize: true,align:'center'}
        )
        // console.log(tableColsOption)
        let tableData = [];
        if (data !== undefined){
            data.forEach(function(sku){
                if (sku === undefined)return null;

                let i = {
                    'sku_number':'',
                    'pic':'',
                    'stock':'',
                    'price':'',
                };
                // console.log(sku)
                sku.forEach(function (skuItem) {
                    i[skuItem.field] = skuItem.value;
                })
                tableData.push(i);
            })
        }

        // console.log(tableData)
        table.render({
            elem: '#' + skuTable.table
            ,data:tableData
            ,limit:100
            // ,skin: 'line'
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,cols: [tableColsOption]
            ,done:function(res,curr,count) {
                //回调执行合并单元格逻辑
                merge(res,mergeField)
            }
        });
    }

    exports('skuTable', skuTable);
});