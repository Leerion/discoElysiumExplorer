const app = new Vue({
  el:'#app',
  data:{
    conversationId:null,
    dialogueId:null,
    elements: [],
    isLoading: false,
    actors: [],
    dialogueSearchForm: {
      dialogue:null,
      actorId:-1,
      softSearch: false
    },
    dialogueSearch:{
      result: [],
      notFound: true
    },
    selectedNode: 
    {
      title: '',
      text: '',
      voiceLine: '',
    },
    showModal: false

  },
  mounted:function(){
      axios({
          method: 'post',
          url: '/site/actors', // make sure your endpoint is correct
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
           } 
      })
      .then(response => {
          this.actors = response.data
      })
      .catch(error => {
          //handle error
          console.log(this);
      }); 

      this.$nextTick(function () {
        $('#selectActor').selectpicker('val', '-1');
        
      })
  },
  methods:{
    refreshActors: function() {
      $('#selectActor').selectpicker("refresh");
    },
    fillConversation:function(inputConversation, inputDialogue) {
      this.conversationId = inputConversation
      this.dialogueId = inputDialogue
    },
    onSubmitDialoguesSearcher:function(evt) {
        evt.preventDefault();
        axios({
              method: 'post',
              url: '/site/search', // make sure your endpoint is correct
              data: {
                dialogue: this.dialogueSearchForm.dialogue,
                actorId: this.dialogueSearchForm.actorId,
                softSearch: this.dialogueSearchForm.softSearch
              },
              headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
               } 
          })
          .then(response => {
              if(response.data.length)
              {
                this.dialogueSearch.result = response.data;
                this.dialogueSearch.notFound = false;
              } else {
                this.dialogueSearch.notFound = true;
              }
          })
          .catch(error => {
              //handle error
              console.log(this);
          });
    },
    onSubmitConversation:function(evt) {
        this.isLoading = true;
        evt.preventDefault();

        axios({
              method: 'post',
              url: '/site/build',
              data: {
                conversationId: this.conversationId
              },
              headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
               } 
          })
          .then(response => {
            try {
              this.elements = response.data;

              cytoscape.use(cytoscapeKlay);
              
              const klayGraph = cytoscape({
                container: $('#cy_klay'), // container to render in
                elements: this.elements,
                layout: {
                  name: 'klay',
                },
                style: [
                    {
                        'selector': '.InnerDemons',

                        'style': {
                            'shape': 'square',
                            'background-color': 'red'
                        }
                    },
                    {
                        selector: '.DialogueFragment',
                        style: {
                            'background-color': 'orange'
                        }
                    },
                    {
                      'selector': 'node',
                      'css': {
                          'content': 'data(label)',
                          'text-valign': 'top',
                          'color': 'black',
                      }
                    },

                    {
                      'selector': 'edge',
                      'style': {
                        'width': 3,
                        'line-color': '#ccc',
                        'target-arrow-color': '#ccc',
                        'curve-style': 'bezier',
                        'target-arrow-shape': 'triangle' // there are far more options for this property here: http://js.cytoscape.org/#style/edge-arrow
                      },
                    },


                    {
                        selector: '.Fork',
                        style: {
                            'background-color': '#8e00ff'
                        }
                    },

                    {
                        selector: '.basic',
                        style: {
                            'background-color': '#1E90FF'
                        }
                    }


                ],

              });

              klayGraph.on('tap', 'node', function(e){
                app.nodeInfo(e.target)
              });

              klayGraph.on('mouseover', 'node', function( e ){
                node = e.target;

                if(node) {
                  node.qtip({
                    content: {
                        text: node.data().title
                    },
                    position: {
                        my: 'top center',
                        at: 'middle center',
                        adjust: {
                            y: 50,
                            mouse: false
                        }
                    },
                    show: {
                        event: 'mouseover'
                    },
                    hide: {
                        event: 'mouseout'
                    },
                    style: {
                        classes: 'qtip-bootstrap'
                    }
                  });
                }
              });


              this.klayGraph = klayGraph
              this.isLoading = false
            } catch(error) {
              console.error(error)
            }
          })
          .catch(error => {
              this.isLoading = false
              console.log(this);
          });
        
    },

    nodeInfo: function(node) {
      node = node.data()
      this.selectedNode['id'] = node.id
      this.selectedNode['title'] = node.title
      this.selectedNode['text'] = node.text
      if(node.voiceLine) {
        this.selectedNode['voiceLine'] = 'assets/AudioClip_aac/'+node.voiceLine
      }
      else {
        this.selectedNode['voiceLine'] = null 
      }

      this.$bvModal.show('bv-modal-example')

    },

    searchNode: function(e) {
      e.preventDefault();

      if(this.klayGraph) 
      {
        var pos = this.klayGraph.nodes("[id = 'node" + this.dialogueId + "']").position();

        this.klayGraph.zoom({
          level: 1.5
        });

        this.klayGraph.center(this.klayGraph.nodes("[id = 'node" + this.dialogueId + "']"));
      }

    },
  }
})

$( ".legend" ).draggable({
  containment: "parent"
});


