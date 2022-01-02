<div id="app">
	<div class="row">
	<div class="col-md-3">
	<div v-bind:class="{'menu_blocker': true, 'menu_blocker_active': isLoading}" >
		<div class="menu-blocker-bg"></div>
		<button class="btn btn-primary spinner_loader" type="button" disabled>
		  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
		  Loading...
		</button>
	</div>	
	<div class="menu">
	<div id="menuList">
		<h3 style="text-align: center;"> Disco Elysium Explorer </h3>
		<div class="card">
    <div class="card-header" id="headingProjectInfo">
      <h5 class="mb-0">
        <button class="btn" data-toggle="collapse" data-target="#projectInfo" aria-expanded="true" aria-controls="projectInfo">
          Project info
        </button>
      </h5>
    </div>

    <div id="projectInfo" class="collapse" aria-labelledby="headingOne" data-parent="#menuList">
      <div class="card-body">
      	
      	<p> This project allow you to visualize and listen dialogues from the Disco Elysium.</p>

      	<p><b> All rights to dialogues and voice line reserved by <a href="https://zaumstudio.com/">studio ZA/UM</a>.</b></p>

      	<p><b> Inspired by <a href="https://disco-reader.gitlab.io/disco-reader/#/">Disco Reader</a>.</b> </p>
      </div>
    </div>
  </div>

		<div class="card">
    <div class="card-header" id="headingDialogueSearcher">
      <h5 class="mb-0">
        <button class="btn" data-toggle="collapse" data-target="#dialogueSearcher" aria-expanded="true" aria-controls="dialogueSearcher">
          Search dialogues
        </button>
      </h5>
    </div>

    <div id="dialogueSearcher" class="collapse" aria-labelledby="headingOne" data-parent="#menuList">
      <div class="card-body">
        <form @submit="onSubmitDialoguesSearcher" method="post" class="forms">
        	<div class="form-group">
	        	<label for="dialoguePhrase">Looking for: </label>
						<input v-model="dialogueSearchForm.dialogue" class="form-control dialoguePhrase">
					</div>
					<div class="form-group">
						<div @click="refreshActors" >
							<label for="selectActor">Told by person: </label>
							<select v-model="dialogueSearchForm.actorId" class="selectpicker form-control dropdown-menu-left" data-live-search="true" id='selectActor'>
							<option value="-1" selected default>Any person </option>
							  <option v-for='actor in actors' :value="actor.actorId">{{ actor.name }} </option>
							  <input type="checkbox" id="checkbox" v-model="dialogueSearchForm.softSearch">
								<label for="checkbox">Soft search</label>
							</select>
						</div>
					</div>
					
					<input type="submit" value="Search" class="btn btn-secondary">  
					
        </form>

        <div class="text-search-result">
	        <div class="list-group" v-if="!dialogueSearch.notFound">
	        	<a @click="fillConversation(r.conversationId, r.dialogueId)" v-for="r in dialogueSearch.result" class="list-group-item list-group-item-action" :title="r.title">
    				{{ r.conversationId }}-{{ r.dialogueId }}
  				</a>
					</div>
				</div>
      </div>
    </div>
  </div>
	
	<div class="card">
    <div class="card-header" id="headingConversationBuilder">
      <h5 class="mb-0">
        <button class="btn" data-toggle="collapse" data-target="#conversationBuilder" aria-expanded="true" aria-controls="conversationBuilder">
          Build conversation
        </button>
      </h5>
    </div>

    <div id="conversationBuilder" class="collapse" aria-labelledby="headingOne" data-parent="#menuList">
      <div class="card-body">
      	<h5 class="forms"> Visualize </h5>
				<form @submit="onSubmitConversation" method="post" class="forms">
					<div class="form-group">
						<label>Conversation Id</label>
					 	<input v-model="conversationId" class="form-control">
					 	<input type="submit" class="btn btn-secondary" value="Build graph">  
					</div>
		    </form>

				<form @submit="searchNode" method="post" class="forms">
					<div class="form-group">
						<label>Dialogue Id</label>
						<input v-model="dialogueId" :disabled="conversationId == null" class="form-control">
						<input type="submit" class="btn btn-secondary" :disabled="conversationId == null" value="Search node">  
					</div>
			  </form>
			</div>
		</div>
	</div>
	</div>

	<div>
        <b-modal id="bv-modal-example" hide-footer>
        <template #modal-title>
          {{ selectedNode.title }}
        </template>
        <div class="d-block">
          <p>{{ selectedNode.text }}</p>
        </div>
        <audio controls v-if="selectedNode.voiceLine  !== null">
          <source :src="selectedNode.voiceLine" type="audio/aac">
          Your browser does not support the audio element.
        </audio>
        
      </div>

      </div>
    </div>
    <div class="col-md-9">

            <div class="legend shadow p-3 mb-5 bg-light rounded"> 
              <div class="inner-legend">
                <b>Legend:</b>
                <ul>
                  <li> 
                    <span class="dot blue"><p> - transition nodes</p></span>
                  </li>
                  <li> 
                    <span class="dot red"><p>  - skill nodes</p></span>
                  </li>
                  <li> 
                    <span class="dot orange"><p> - person nodes</p></span>
                  </li>
                </ul>
              </div>
            </div>
              <div id="cy_klay"></div>
        </div>
    </div>
</div>


<script src="/js/app.js"> </script>