<template>
    <div id="myTree">
        <tree_item :data="items"></tree_item>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                items: [{id: 1, name: 'My Tree', children: [
                    {id: 2, name: 'hello1'},
                    {id: 3, name: 'wat2'},
                    {id: 4, name: 'child folder3', children: [
                        {id: 5, name: 'child folder4', children: [
                            {id: 6, name: 'hello5'},
                            {id: 7, name: 'wat6'}
                        ]},
                        {id: 8, name: 'hello7'},
                        {id: 9, name: 'wat8'},
                        {id: 10, name: 'child folder9', children: [
                            {id: 11, name: 'hello10'},
                            {id: 12, name: 'wat11'}
                        ]}
                    ]}
                ]}],
                getChecked: function() {
                    var r = [];
                    jQuery('#myTree input:checked').each(function() {
                        r.push(jQuery(this).val());
                    });
                    return r;
                }
            };
        },
        mounted() {},
        props: ['message']
    }
</script>

