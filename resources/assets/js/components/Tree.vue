<template>
    <tree_item :data="items"></tree_item>
</template>

<script>
    export default {
        data: function () {
            return {
                items: [{name: 'My Tree', children: [
                    {type: 'file', name: 'hello1'},
                    {type: 'file', name: 'wat2'},
                    {type: 'folder', name: 'child folder3', children: [
                        {type: 'folder', name: 'child folder4', children: [
                            {type: 'file', name: 'hello5'},
                            {type: 'file', name: 'wat6'}
                        ]},
                        {type: 'file', name: 'hello7'},
                        {type: 'file', name: 'wat8'},
                        {type: 'folder', name: 'child folder9', children: [
                            {type: 'file', name: 'hello10'},
                            {type: 'file', name: 'wat11'}
                        ]}
                    ]}
                ]}]
            };
        },
        mounted() {
        },
        props: ['message']
    }
</script>

