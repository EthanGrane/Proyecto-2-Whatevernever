<script setup>
import { onMounted } from 'vue';
import { InitializeMap, AddMarkerToMap, SetFriends,ReloadMapMarkers, AddFriendMarkerToMap, FlyToPosition, GetMap  } from "../../composables/MapUtils.js";

onMounted(() => 
{
    const imagePath = "/images/";
    const friendsConnected = 
    [
        { name: "Adrian",               profilePicture: imagePath + 'ProfilePicture_0.jpg', lng: 2.02690062977777, lat: 41.4113279581609 },
        { name: "Oliver Ros Lillo",     profilePicture: imagePath + 'ProfilePicture_1.jpg', lng: 2.349057569854807, lat: 48.85281416527957},
        { name: "Paul",                 profilePicture: imagePath + 'ProfilePicture_2.jpg', lng: -3.711798985923764, lat: 40.39401545431315 },
        { name: "Holden",               profilePicture: imagePath + 'ProfilePicture_3.jpg', lng: -5.95236496667161, lat: 37.38708172425217 },
        { name: "Holden",               profilePicture: imagePath + 'ProfilePicture_4.jpg', lng: -73.96151983821908, lat: 40.80372848997743 },
        { name: "Holden",               profilePicture: imagePath + 'ProfilePicture_5.jpg', lng: 14.21623662321312, lat: 40.87742787180281 },
        { name: "Holden",               profilePicture: imagePath + 'ProfilePicture_6.jpg', lng: 13.443069286288392, lat: 52.55075703102233 },
        { name: "Holden",               profilePicture: imagePath + 'ProfilePicture_7.jpg', lng: -0.2007759956829318, lat: 51.48297928478809 },
        { name: "Alberto",               profilePicture: imagePath + 'ProfilePicture_9.jpg', lng: -78.8056285294565, lat: -5.710376871065819 },
    ];

    const center = { lng: 41.4113279581609, lon: 2.02690062977777 };
    const map = InitializeMap(center);
    map.on('load', () => 
    {
        // Code here!
        SetFriends(friendsConnected);
        ReloadMapMarkers(map);

        AddMarkerToMap(-7,40,map,"Test");
    });
})
</script>

<template>
    <div id="map">
    </div>
</template>

<!--
<template>
    <div class="demo" id="edit-demo">
        <div class="viewport" @click="select(null)" @mousedown.capture="blockEvents" @wheel.capture="blockEvents">
            <screen ref="screen">
                <g v-for="edge in graph.edges" @click.stop="select(edge)" :key="edge.id">
                    <edge :class="selection && selection.id === edge.id && 'selected'"
                          :data="edge"
                          :nodes="graph.nodes">
                    </edge>
                </g>
                <g v-for="node in graph.nodes" :key="node.id">
                    <node :data="node" ref="node" :class="isSelected(node) && 'selected'" :textSelect="node.textSelect" :useDrag="node.useDrag">
                        <div v-html="node.html" @click.stop="select(node)">
                        </div>
                    </node>
                </g>
            </screen>
        </div>
        <div class="sidebar">
            <codemirror v-model="editText" :options="{
          mode: 'text/javascript',
          theme: 'default',
          lineWrapping: true,
          scrollbarStyle: null,
          styleActiveLine: true,
          line: true,
        }"
                        style="font-size: 13.3333px; font-family: monospace; -webkit-text-size-adjust: 100%; height: 100%"
            ></codemirror>
        </div>
    </div>
</template>

<script>
import Screen from '../components/Screen.vue'
import Node from '../components/Node.vue'
import Edge from '../components/Edge.vue'
import graph from '../graph'
import pretty from 'pretty'
import stringify from 'javascript-stringify'
import { Codemirror } from 'vue-codemirror'
// import 'codemirror/mode/javascript/javascript.js'
// import 'codemirror/lib/codemirror.css'

export default {
    components: {
        Screen,
        Node,
        Edge,
        Codemirror
    },
    data() {
        return {
            graph: new graph(),
            selection: null,
            editText: '/* click on a node or edge to start editing */',
        }
    },
    methods: {
        select (obj) {
            this.selection = obj
            if (!this.selection) {
                this.editText = '/* click on a node or edge to start editing */'
                return
            }
            const editText = { ...obj }
            delete editText.pathd
            if (editText.html) {
                editText.html = "\n" + pretty(editText.html) + "\n"
            }
            this.editText = stringify(editText, null, 2)
                .replace(/\\n/g, "\n")
                .replace(/html: '([^]*)\s'/g, 'html: `$1\n`')
        },
        applyChanges () {
            if (!this.selection) {
                return
            }
            try {
                const edit = eval('('+this.editText+')')
                Object.assign(this.selection, edit)
                this.$nextTick(() => {
                    this.$refs.node.forEach(node => {
                        node.fitContent()
                    })
                })
            } catch (_) {
                console.log('TODO invalid code')
            }
        },
        isSelected (node) {
            return this.selection
                && this.selection.id === node.id
        },
        /**
         * HACKS
         * support shortcut .no-drag and .no-wheel classes
         * to disable dragging and mouse-wheel behavior from editable html
         */
        blockEvents (e) {
            const path = e.path || e.composedPath?.();
            if (path?.find(el => el.classList?.contains('no-drag'))) { // @mousedown
                const pz = this.$refs.screen.panzoom
                pz.options.preventMouseEventsDefault = false // enable default events (text select, input click)
                document.addEventListener('mouseup', () => {
                    pz.options.preventMouseEventsDefault = true
                }, { once: true })
                e.stopPropagation() // disable node drag
            }
            if (path?.find(el => el.classList?.contains('no-wheel'))) { // @wheel
                e.stopPropagation() // disable wheel zoom
            }
        },
    },
    mounted () {
        this.graph.createNode({
            id: 'a',
            html: '<h5>A</h5>'
        })
        this.graph.createNode({
            id: 'b',
            x: 200,
            y: 200,
            textSelect: false,
            useDrag: true,
            html:
                `<div><h4>B</h4><p>Subtitle</p><button>Yo</button></div>`
        })
        this.graph.createNode({
            id: 'c',
            x: -100,
            y: 150,
            textSelect: false,
            useDrag: true,
            html: `<div> <h4>okay</h4> <textarea type="text" class="no-drag">Some text here</textarea><br/><select class="no-drag" name="cars" id="cars"><option value="volvo">Volvo</option><option value="saab">Saab</option><option value="mercedes">Mercedes</option><option value="audi">Audi</option></select></div>`
        })
        this.graph.createNode({
            id: 'd',
            x: 340,
            textSelect: false,
            useDrag: true,
            html: `<div>Okay</div>`
        })
        this.graph.createEdge({
            id: 'a:b',
            from: 'a',
            to: 'b',
            toAnchor: { x: '50%', y: '50%', snap: 'rect' },
            type: 'smooth'
        })
        this.graph.createEdge({ id: 'c:d', from: 'c', to: 'd', type: 'smooth' })
        this.$nextTick(() => {
            this.$refs.screen.zoomNodes(this.graph.nodes, {scale: 1})
        })
    },
    watch: {
        editText: 'applyChanges',
    },
}
</script>

<style>
#edit-demo .CodeMirror {
    width: 100%;
    height: 500px;
    margin: 0;
    overflow: hidden;
    position: relative;
    background-color: #f1f1f1;
    border: 1px solid #f1f1f1;
}
#edit-demo .node .background {
    /* background-color: #ccc; */
}

#edit-demo .node .content > div {
    padding: 25px;
}

#edit-demo .node .content h4,h5,p {
    margin: 0
}

#edit-demo .node:hover .background {
    background-color: rgb(90 200 90);
}

#edit-demo .node.selected .content {
    background-color: rgba(100, 200, 100, 1);
    box-shadow: 0px 0px 0px 2px #333;
}

#edit-demo .node .content {
    cursor: pointer;
}

#edit-demo .edge {
    cursor: pointer;
}
#edit-demo .edge:hover {
    /* stroke-width: 4 */
    stroke: rgb(90 200 90)
}
#edit-demo .edge.selected {
    /* stroke-width: 4 */
    stroke: #333
}
</style>
-->
