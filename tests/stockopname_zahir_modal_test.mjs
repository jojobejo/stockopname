// Run with Node: node tests/stockopname_zahir_modal_test.mjs
import assert from 'node:assert/strict';
const previousWindow = globalThis.window;
const previousFormData = globalThis.FormData;
const nodes = new Map();
const events = new Map();
let requestCount = 0;
let request;
const input = {files:[], validityMessage:'', setCustomValidity(message){this.validityMessage=message;}};
function node(key) {
    if (!nodes.has(key)) nodes.set(key, {
        length:1, values:{}, classes:{}, props:{}, textValue:'',
        data(name,value){if(arguments.length===1)return this.values[name];this.values[name]=value;return this;},
        find(selector){if(selector==='input[type="file"]')return {0:input};return node(key+' '+selector);},
        on(name,selector,handler){events.set(key+' '+name+' '+(typeof selector==='string'?selector:''),handler||selector);return this;},
        text(value){this.textValue=value;return this;},
        prop(name,value){this.props[name]=value;return this;},
        toggleClass(name,value){this.classes[name]=value;return this;},
        css(){return this;}, attr(){return this;}
    });
    return nodes.get(key);
}
function jquery(selector){return node(selector);}
jquery.ajax=function(options){requestCount++;request=options;return {done(){return this;},fail(){return this;},always(){return this;}};};
try {
    globalThis.window={jQuery:jquery};
    globalThis.FormData=class {constructor(){this.fields={operation:'import'};}get(k){return this.fields[k];}};
    await import('../assets/js/stockopname-zahir-import.js');
    window.StockopnameZahirImport.init({url:'https://test.invalid/zahir-csv?embedded=1'});
    const bodyKey='#zahirCsvImportModal .zahir-import-modal-body';
    const change=events.get(bodyKey+' change input[type="file"]');
    const submit=events.get(bodyKey+' submit form');
    const button=node(bodyKey+' .zahir-import-submit');
    const filename=node(bodyKey+' .zahir-file-name');
    const meta=node(bodyKey+' .zahir-file-meta');
    input.files=[{name:'zahir.csv',size:2048}];change();
    assert.equal(filename.textValue,'zahir.csv');assert.match(meta.textValue,/siap diimpor/);assert.equal(button.props.disabled,false);
    assert.equal(node(bodyKey+' .zahir-import-current,.zahir-import-panel > .alert,.zahir-import-errors,.zahir-result').classes['d-none'],true);
    console.log('PASS: selected file replaces old status and enables import');
    input.files=[{name:'dump.sql',size:2048}];change();assert.equal(button.props.disabled,true);assert.ok(input.validityMessage);
    input.files=[{name:'large.csv',size:5242881}];change();assert.equal(button.props.disabled,true);
    input.files=[{name:'empty.csv',size:0}];change();assert.equal(button.props.disabled,true);
    console.log('PASS: invalid extension, oversize and empty file cannot be submitted');
    input.files=[];change();assert.equal(filename.textValue,'Pilih file CSV');assert.equal(button.props.disabled,true);
    console.log('PASS: clearing file resets selection without misleading CSV data message');
    input.files=[{name:'zahir.csv',size:2048}];change();
    const form={action:'https://test.invalid/zahir-csv?embedded=1',reportValidity(){return !input.validityMessage;}};
    submit.call(form,{preventDefault(){}});
    assert.equal(requestCount,1);assert.equal(request.type,'POST');assert.equal(request.data.get('operation'),'import');assert.equal(button.props.disabled,true);
    submit.call(form,{preventDefault(){}});assert.equal(requestCount,1);
    console.log('PASS: one direct import POST, no preview request, double-submit blocked');
} finally {globalThis.window=previousWindow;globalThis.FormData=previousFormData;}
