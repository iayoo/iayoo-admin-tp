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

    // let skuList = [
    //     {'field':'颜色',child:[{value:'红色'},{value:'蓝色'}]},
    //     {'field':'内存',child:[{value:'128G'},{value:'256G'}]},
    // ];

    let skuData = [
        {'field':'颜色',child:[{value:'红色'},{value:'蓝色'}]},
        {'field':'内存',child:[{value:'128G'},{value:'256G'}]},
    ]

    /**
     * 笛卡尔积
     * @returns {any|*[]|U}
     */
    function descartes() {
        if (arguments[0].length < 2){
            // field,value
            let res = {}
            console.log(arguments[0])
            res.child = [arguments[0]]
            return res;
        }
        return [].reduce.call(arguments[0], function(col, set) {
            let res = {
                child: []
            };
            if (res)
            if (col.child === undefined || set.child.length<=0) {
                res.child = [col.child]
                return res
            }
            col.child.forEach(function(c) {
                if (col.field !== undefined) c.field = col.field;
                set.child.forEach(function(s) {
                    if (set.field !== undefined) s.field = set.field;
                    let t = [].concat(Array.isArray(c) ? c : [c]);
                    t.push(s);
                    res.child.push(t);
                })
            });
            return res;
        });
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
        var indexPoint = [0];
        var data = res.data;
        var mergeIndex = 0;//定位需要添加合并属性的行数
        var mark = 1; //这里涉及到简单的运算，mark是计算每次需要合并的格子数
        //列名集合["orderNumber","reagentName","chineseVulgo","component","specifications","componentShelf","remarks"];
        /**
         * 执行第一列，已序号分组为准，产生分割点并保存
         */
        let trArr = $(".layui-table-body>.layui-table").find("tr");//所有行
        // console.log(trArr)
        // trArr.forEach()
        for (let i = 1; i < res.data.length; i++) { //这里循环表格当前的数据
            let tdCurArr = trArr.eq(i).find("td").eq(0);//获取当前行的当前列
            let tdPreArr = trArr.eq(mergeIndex).find("td").eq(0);//获取相同列的第一列
            console.log(data[i - 1]);
            console.log(data[i]);

            mergeField.forEach(function (fieldName) {
                if (data[i][fieldName] === data[i - 1][fieldName]) { //后一行的值与前一行的值做比较，相同就需要合并
                    mark += 1;
                    //相同列的第一列增加rowspan属性
                    tdPreArr.each(function () {
                        $(this).attr("rowspan", mark);
                    });
                    //当前行隐藏
                    tdCurArr.each(function () {
                        $(this).css("display", "none");
                    });
                }else {
                    //保存分割点
                    indexPoint.push(i)
                    mergeIndex = i;
                    mark = 1;//一旦前后两行的值不一样了，那么需要合并的格子数mark就需要重新计算
                }
            })


        }
        //补全最后一个分割点
        indexPoint.push(res.data.length)
        console.log("合并索引点集合：",indexPoint)
//依据拿到的分割点，对其他6列进行合并处理
//             for(var i = 0;i<indexPoint.length;i++){
//                 var startIndex=0;
//                 if(i!=0){
//                     startIndex = indexPoint[i-1];
//                 }
//                 for(var j=startIndex;j<indexPoint[i];j++){
//                     //以第一列产生的区域分割点为基准，执行后面6列合并逻辑
//                     mergeSomeRows(1,startIndex,indexPoint[i],trArr,data,'reagentName');
//                     mergeSomeRows(2,startIndex,indexPoint[i],trArr,data,'chineseVulgo');
//                     mergeSomeRows(3,startIndex,indexPoint[i],trArr,data,'component');
//                     mergeSomeRows(4,startIndex,indexPoint[i],trArr,data,'specifications');
//                     mergeSomeRows(5,startIndex,indexPoint[i],trArr,data,'componentShelf');
//                     mergeSomeRows(6,startIndex,indexPoint[i],trArr,data,'remarks');
//                 }
//             }
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
        layer.prompt(function(val, pageIndex){
            // 关闭弹窗
            layer.close(pageIndex);
            skuData[Number(index)].child.push({value:val,skuId:1});
            skuTable.renderForm();
            skuTable.render();
        });
    }
    window.handleAddSku = function (){
        //默认prompt
        layer.prompt(function(val, index){
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
        let data = descartes(skuData);
        console.log("渲染 sku table");
        console.log(data)
        let mergeField = []
        skuData.forEach(function (sku) {
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
        if (data.child !== undefined){
            data.child.forEach(function(sku){
                if (sku === undefined)return null;
                let i = {
                    'sku_number':'',
                    'pic':'',
                    'stock':'',
                    'price':'',
                };
                console.log("sku item data")
                console.log(sku)
                sku.forEach(function (skuItem) {
                    i[skuItem.field] = skuItem.value;
                })
                tableData.push(i);
            })
        }

        console.log(tableData)
        table.render({
            elem: '#' + skuTable.table
            ,data:tableData
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,cols: [tableColsOption]
            ,done:function(res,curr,count) {
                //回调执行合并单元格逻辑
                // merge(res,mergeField)
            }
        });
    }

    exports('skuTable', skuTable);
});