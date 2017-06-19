<?php
// font-family: 'Roboto', sans-serif;
// font-family: 'Source Code Pro', monospace;

/**
 * @var $content
 */
?>
<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="data:;base64,iVBORw0KGgo=">
<style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Roboto|Source+Code+Pro');

body {
	margin: 0;
	font-family: 'Roboto', sans-serif;
    font-size: 12px;
}

.content {
	background: #fff;
	padding: 0 20px;
}

.content-body {
    padding-top: 5px;
}

.log-item {
    border: 1px solid #ccc;
    padding: 5px 50px;
    margin-top: 5px;
}

.outcome.request,
.income.response {
    margin-left: 50px;
}

.list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.list li {
    margin-left: 15px;
}

.caption {
    text-transform: capitalize;
}

.caption:after {
    content: ':';
    padding-left: 1px;
}

.general .caption {
    display: inline-block;
    min-width: 100px;
}

.params .item .caption {
    text-transform: none;
    font-family: 'Source Code Pro', monospace;
}

.method .value {
    text-transform: uppercase;
}

.value{
    font-family: 'Source Code Pro', monospace;
    color: #434343;
    padding-left: 10px;
}

.item-date {
    padding-top: 10px;
    font-size: 10px;
    color: #434343;
    font-family: 'Source Code Pro', monospace;
    text-align: right;
}

.content-type .value {
    text-transform: uppercase;

}
.content-type {
    width: 60%;
    border-bottom: 1px solid #ddd;
}

.log-item > div,
.log-item > ul {
    margin-top: 5px;
}

.item-caption {
    position: relative;
    text-transform: uppercase;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    background: #999;
    padding: 4px;
    padding-top: 6px;
    cursor: pointer;
}

.log-item.error {
    border-top-color: #e64c4c;
    border-top-width: 3px;
    background: #ddd;
}

.error .item-caption {
    background: #e64c4c;
}

.error-type {
    color: #e64c4c;
    text-transform: uppercase;
    font-size: 14px;
    font-weight: 600;
    display: inline-block;
    border-right: 1px solid #999;
    line-height: 60px;
    padding-right: 50px;
    float: left;
    position: relative;
}

.error .info {
    display: inline-block;
    padding-left: 50px;
}

.message, .file, .line {
    display: block;
    line-height: 20px;
}

.info .caption {
    min-width: 53px;
    display: inline-block;
    text-align: right;
}

.context {
    margin-top: 10px;
}

.variable-data {
    margin-left: 15px;
    display: block;
    position: relative;
}

.variable-data .name {
    color: #000;
    position: relative;
    top: 1px;
    float: left;
    cursor: pointer;
}

.variable-data .name::before {
    content: '$';
    color: #434343;
    padding-right: 1px;
}

.object .variable-data .name::after {
    content: ':';
    color: #434343;
}
.object .variable-data .name::before, .array .variable-data .name::before {
    display: none;
}

.array .variable-data .name::after {
    content: '=>';
    padding-left: 16px;
}

.variable-data .type {
    font-size: 10px;
    text-transform: uppercase;
}

.var-value {
    position: relative;
}

.string > .var-value::before {
    content: "'";
}

.string > .var-value::after {
    content: "'";
}

.string > .var-value {
    color: #237549;
}

.boolean > .var-value {
    color: #87209e;
}

.integer > .var-value {
    color: #20569e;
}

.float > .var-value {
    color: #9e5620;
}

.resource > .var-value {
    color: #ce1414;
}

.object > .var-value::before {
    content: '{';
}

.object > .var-value::after {
    content: '}';
}

.array > .var-value::before {
    content: '[';
}

.array > .var-value::after {
    content: ']';
}

.class-name {
    font-style: italic;
    color: #333ea0;
}

.resource > .var-value::before {
    content: '<';
}

.resource > .var-value::after {
    content: '>';
}

.value.array,
.value.object {
    display: inline-block;
}

.variable-data.object > .value > .var-value > .variable-data,
.variable-data.array > .value > .var-value > .variable-data {
    display: none;
}

.variable-data.object.on > .value > .var-value > .variable-data,
.variable-data.array.on > .value > .var-value > .variable-data {
    display: block;
}

.variable-data.object::before,
.variable-data.array::before {
    content: '';
    position: absolute;
    top: 5px;
    left: -8px;
    border: 3px solid transparent;
    border-left-color: #434343;
    border-left-width: 5px;
}

.variable-data.object.on::before,
.variable-data.array.on::before {
    content: '';
    position: absolute;
    top: 7px;
    left: -9px;
    border: 3px solid transparent;
    border-top-color: #434343;
    border-top-width: 5px;
}

.args .variable-data.object::before,
.args .variable-data.array::before,
.args .variable-data.object.on::before,
.args .variable-data.array.on::before {
    display: none;
}

.trace .entry {
    margin-left: 15px;
}
.entry .line {
    display: inline;
}
.function > .value {
    color: #081d73;
}
.function > .args::before {
    content: '(';
    color: #081d73;
}

.function > .args::after {
    content: ')';
    color: #081d73;
}
.args .variable-data {
    display: inline-block;
    margin: 0;
}
.args .variable-data:last-of-type:after {
    display: none;
}
.args .variable-data::after {
    content: ',';
}
.args .variable-data:first-of-type > .value {
    padding-left: 0;
}

.entry .step {
    font-family: 'Source Code Pro', monospace;
    font-size: 12px;
    color: #081d73;
}

.entry .step:after {
    content: '>';
    font-family: 'Source Code Pro', monospace;
    color: #081d73;
}
.entry > .line {
    padding: 0;
}

.entry > .line:before {
    content: '(';
}
.entry > .line:after {
    content: ')';
}

/**********************************************************/
.json-item {
    margin-left: 15px;
    position: relative;
}
.json-item .name {
    font-family: 'Source Code Pro', monospace;
    color: #434343;
}
.json-item.on > .name {
    color: #000;
}
.json-item .name:after {
    content: ':';
}
.json-item.object > .value:before {
    content: '{';
}
.json-item.object > .value:after {
    content: '}';
}
.json-item.array > .value:before {
    content: '[';
}
.json-item.array > .value:after {
    content: ']';
}

.json-item.string > .value:before,
.json-item.string > .value:after {
    content: "'";
    position: absolute;
}
.json-item.string > .value:before {
    left: 2px;
}

.json-item.integer > .value {
    color: #20569e;
}
.json-item.string > .value {
    color: #237549;
    position: relative;
}
.json-item.boolean > .value {
    color: #87209e;
}
.json-item.float > .value,
.json-item.double > .value {
    color: #9e5620;
}

.json-item .value {
    display: inline;
}
.json-item .name {
    display: inline-block;
}

.json-item.object:not(.empty) > .name:hover,
.json-item.array:not(.empty) > .name:hover {
    color: #000;
    cursor: pointer;
}

.json-item.object:not(.empty)::before,
.json-item.array:not(.empty)::before {
    content: '';
    position: absolute;
    top: 5px;
    left: -8px;
    border: 3px solid transparent;
    border-left-color: #434343;
    border-left-width: 5px;
}

.json-item.object.on::before,
.json-item.array.on::before {
    content: '';
    position: absolute;
    top: 7px;
    left: -9px;
    border: 3px solid transparent;
    border-top-color: #434343;
    border-top-width: 5px;
}

.json-item.object.on > .value,
.json-item.array.on > .value {
    display: inline;
}
.json-item.object > .value,
.json-item.array > .value {
    display: none;
}

.json-item.object:after,
.json-item.array:after {
    font-family: 'Source Code Pro', monospace;
    color: #434343;
    padding-left: 10px;
}
.json-item.object:after {
    content: '{...}';
}
.json-item.object.empty:after {
    content: '{} empty';
}
.json-item.array:after {
    content: '[...]';
}
.json-item.array.empty:after {
    content: '[] empty';
}

.json-item.object.on:after,
.json-item.array.on:after {
    display: none;
}

/***********************************************/
.log-item > .general {
    position: relative;
}
.log-item.request .item-caption > span,
.log-item.response .item-caption > span {
    visibility: hidden;
}

.log-item .general .method > .caption,
.log-item .general .url > .caption,
.log-item .general .remote-addr,
.log-item .headers,
.log-item .params,
.log-item .general .message,
.log-item .content-wrapper,
.log-item.error .context,
.log-item.error .trace {
    display: none;
}

.log-item.request .general .method > .value {
    position: absolute;
    top: -25px;
    left: -5px;
    font-size: 14px;
    color: #fff;
    font-weight: 600;
    font-family: 'Roboto', sans-serif;
}
.log-item.request .general .url > .value {
    position: absolute;
    top: -27px;
    left: 105px;
    font-size: 14px;
    color: #fff;
}
.log-item .item-date {
    padding-top: 0;
}
.log-item.response .general .protocol {
    position: absolute;
    top: -25px;
    left: 5px;
    font-size: 14px;
    color: #fff;
    font-weight: 600;
}
.log-item.response .general .code {
    position: absolute;
    top: -25px;
    left: 80px;
    font-size: 14px;
    color: #fff;
    font-weight: 600;
}

.log-item.on.request .item-caption > span,
.log-item.on.response .item-caption > span {
    visibility: visible;
}

.log-item.request.on .general .method > .caption,
.log-item.request.on .general .url > .caption {
    display: inline-block;
}

.log-item.on .general .method > .caption,
.log-item.on .general .url > .caption,
.log-item.on .general .remote-addr,
.log-item.on .headers,
.log-item.on .params,
.log-item.on .general .message,
.log-item.on .content-wrapper,
.log-item.on.error .info .message > .caption {
    display: initial;
}
.log-item.on.error .context,
.log-item.on.error .trace {
    display: block;
}

.log-item.request.on .general .method > .value,
.log-item.request.on .general .url > .value {
    position: static;
    color: #434343;
    font-size: 12px;
    font-weight: 400;
    font-family: 'Source Code Pro', monospace;
}
.log-item.on .item-date {
    padding-top: 10px;
}
.log-item.response.on .general .protocol,
.log-item.response.on .general .code {
    position: static;
    font-size: 12px;
    font-weight: 400;
    color: #000
}

/**************************************************/
.log-item .item-caption:before {
    position: absolute;
    content: '';
    border: 9px solid transparent;
    border-left-width: 9px;
    border-left-color: #999;
    left: -16px;
    top: 4px;
}
.log-item .item-caption:after {
    position: absolute;
    content: '';
    border: 7px solid transparent;
    border-left-width: 7px;
    border-left-color: #fff;
    left: -15px;
    top: 6px;
}
.log-item.on .item-caption:before {
    position: absolute;
    content: '';
    border: 9px solid transparent;
    border-top-width: 9px;
    border-top-color: #999;
    left: -23px;
    top: 11px;
}
.log-item.on .item-caption:after {
    position: absolute;
    content: '';
    border: 7px solid transparent;
    border-top-width: 7px;
    border-top-color: #fff;
    left: -21px;
    top: 12px;
}

/*****************************************************/
.xml-item {
    margin-left: 15px;
    position: relative;
}

.xml-hdr,
.xml-item .name {
    font-family: 'Source Code Pro', monospace;
    color: #78278a;
}
.xml-item .name:after {
    content: '>';
}
.xml-item .name:before {
    content: '<';
}
.xml-item .name.back:before {
    content: '</';
}

.xml-item.leaf.empty .name:after {
    content: '/>';
}
.xml-item.leaf.empty .name.back {
    display: none;
}

.xml-item > .value {
    padding-left: 0;
    display: inline;
    color: #000;
}

.xml-item.leaf.empty > .value {
    color: #999;
}

.xml-item .name {
    display: inline-block;
}
.xml-item.node > .name:hover {
    cursor: pointer;
}

.xml-item.node::before {
    content: '';
    position: absolute;
    top: 5px;
    left: -8px;
    border: 3px solid transparent;
    border-left-color: #434343;
    border-left-width: 5px;
}
.xml-item.node.on::before {
    content: '';
    position: absolute;
    top: 7px;
    left: -9px;
    border: 3px solid transparent;
    border-top-color: #434343;
    border-top-width: 5px;
}
.xml-item.node.on > .value {
    display: inline;
}
.xml-item.node > .value {
    display: none;
}

.xml-item.node:after {
    font-family: 'Source Code Pro', monospace;
    color: #434343;
    padding-left: 10px;
}
.xml-item.node:after {
    content: '...';
}

.xml-item.node.on:after {
    display: none;
}

.xml-item.node > .name.back {
    display: none;
}
.xml-item.node.on > .name.back {
    display: inline-block;
}
</style>
<title>Remote Logger</title>
</head>
<body>

	<div class="content"><?= $content ?></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.variable-data.object:not(.empty)').on('click', '.name', {parent:'.variable-data'}, onSlide);
        $('.variable-data.array:not(.empty)').on('click', '.name', {parent:'.variable-data'}, onSlide);

        $('.json-item.object:not(.empty)').on('click', '.name', {parent:'.json-item'}, onSlide);
        $('.json-item.array:not(.empty)').on('click', '.name', {parent:'.json-item'}, onSlide);

        $('.log-item').on('click', '.item-caption', {parent:'.log-item'}, onSlide);

        $('.xml-item.node:not(.empty)').on('click', '.name', {parent:'.xml-item'}, onSlide)
    });

    function onSlide(e) {
        e.stopPropagation();

        var parent = $(this).closest(e.data.parent);
        parent.toggleClass('on');
    }

</script>
</body>
</html>
