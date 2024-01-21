<template>
    <div class="">
      <div class="above-icon-wrapper">
        <span class="above-icon">
          <svg width="23" height="23" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.0074 6.26869L10.0123 10.9466M10.0211 19.3667C15.1668 19.3613 19.3725 15.1468 19.3671 10.0012C19.3617 4.85554 15.1472 0.649861 10.0016 0.655247C4.8559 0.660633 0.65022 4.87512 0.655605 10.0208C0.660991 15.1664 4.87548 19.3721 10.0211 19.3667Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      </div>
      <div class="bot-wrapper" @click="toggleChat">
        <img src="/images/Group 1000000924.png" alt="" class="bot">
      </div>
      <div v-if="showChat == true" class="convo">
        <div class="convo-title grid-center">
          <ChatSVG />
          <span @click="closeChat">X</span>
        </div>
        <div class="convo-body" id="convoBody">
          <div class="convo-wrapper left-convo align">
            <img src="/images/Group 1000000924.png" alt="" class="convo-img">
            <p>Welcome to AskBardy</p>
          </div>
          <div v-for="message in messages">
            <div  class="convo-wrapper right-convo align">
              <p>{{ message.user_message }}</p>
              <img :src="'https://askbardy.sfo3.cdn.digitaloceanspaces.com/'+$store.state.auth.path" alt="" class="convo-img">
            </div>  
            <div class="convo-wrapper left-convo align">
              <img src="/images/Group 1000000924.png" alt="" class="convo-img">
              <p>{{ message.ai_message }}</p>
            </div>
          </div>
          <div v-if="last_message" class="convo-wrapper right-convo align">
            <p>{{last_message}}</p>
            <img :src="'https://askbardy.sfo3.cdn.digitaloceanspaces.com/'+$store.state.auth.path" alt="" class="convo-img">
          </div>
          <div v-if="waiting_message" class="convo-wrapper left-convo align">
            <img src="/images/Group 1000000924.png" alt="" class="convo-img">
            <!-- <p>{{waiting_message}}</p> -->
            <div class="dots-3"></div>
          </div>
        </div>
        <form class="convo-footer grid-center relative" @submit="setConversation">
          <input type="text" v-model="user_message" class="convo-input" placeholder="Type your message...">
          <svg @click="setConversation" class="send-icon pointer" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.58251 10.2376L10.2675 7.54512M5.55001 4.74012L11.9175 2.61762C14.775 1.66512 16.3275 3.22512 15.3825 6.08262L13.26 12.4501C11.835 16.7326 9.49501 16.7326 8.07001 12.4501L7.44001 10.5601L5.55001 9.93012C1.26751 8.50512 1.26751 6.17262 5.55001 4.74012Z" stroke="#767272" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <!-- <svg class="other-icon pointer" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_157_2674)">
            <path d="M6.00008 11.3333V7.33325M6.00008 7.33325L4.66675 8.66659M6.00008 7.33325L7.33341 8.66659" stroke="#767272" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14.6666 6.66658V9.99992C14.6666 13.3333 13.3333 14.6666 9.99992 14.6666H5.99992C2.66659 14.6666 1.33325 13.3333 1.33325 9.99992V5.99992C1.33325 2.66659 2.66659 1.33325 5.99992 1.33325H9.33325" stroke="#767272" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14.6666 6.66658H11.9999C9.99992 6.66658 9.33325 5.99992 9.33325 3.99992V1.33325L14.6666 6.66658Z" stroke="#767272" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            <defs>
            <clipPath id="clip0_157_2674">
            <rect width="16" height="16" fill="white"/>
            </clipPath>
            </defs>
          </svg> -->
        </form>
      </div>
    </div>
  </template>
  
  <script >
  import axios from "axios"
  import ChatSVG from "../SVGs/ChatSVG.vue"
  
  let aiBackUrl = import.meta.env.VITE_AIBACK_URL
  export default {
    name: 'Chatbot',
    components:{
      ChatSVG,
    },
    data() {
      return {
        showChat: false,
        ai_message: "hi there",
        user_message: "",
        last_message: "",
        waiting_message: "",
        word_balance: null,
        messages: [],
        textInput: "",
      }
    },
    created() {
        
        this.getMessages()
      let scroolInterval = setInterval(() => {
        const elem = document.querySelector('#convoBody')
        if (elem) {    
          elem && (elem.scrollTop = elem.scrollHeight)
          elem && (elem.behavior = 'smooth')  
          clearInterval(scroolInterval)
        }
      },500)
    },
    methods: {
      getMessages() {
        axios.get(`/api/messages`)
            .then(res => {
            this.messages = res.data.messages
            console.log(res);
            
            })     
        
      },  
      getBalance() {
        this.$store.state.company.id && axios.get(`/api/plan/company/${this.$store.state.company.id}`,{
          headers: {
            Authorization : `Bearer ${localStorage.getItem("access_token")}`
            }
          }).then(res => {
            if(res.data && res.data.company.company_balances) {
              this.word_balance = res.data.company.company_balances.word_balance
            }
          })
      },
      toggleChat() {
        this.showChat = !this.showChat
      },
      closeChat() {
        this.showChat = false
      },
      setConversation(e) {
        this.getBalance()
        e.preventDefault()
        this.last_message = this.user_message
        const message = this.user_message
        this.user_message = ""
        this.waiting_message = "..."
        if (this.word_balance > 0) {
          this.sendMessageToAiBack(message)
        } else {
          this.waiting_message = "Upgrade your plan"
        }    
      },
      sendMessageToAiBack(message){
        let scroolInterval = setInterval(() => {
        const elem = document.querySelector('#convoBody')
        if (elem) {    
          elem && (elem.scrollTop = elem.scrollHeight)
          elem && (elem.behavior = 'smooth')  
          clearInterval(scroolInterval)
        }
      },500)
        const body = {
          platform_ref:'askbardy_company_id_',
          company_id: this.$store.state.company.id,
          company:this.$store.state.company.name,  
          message:message
        };
        axios.post(`${aiBackUrl}/response/internal-message`,body)
        .then(response => {
          console.log(response.data);
          let user_id = (this.$store.state.auth && this.$store.state.auth.name) ? null : this.$store.state.auth.id
          this.ai_message = response.data
            axios.post("/api/internalMessages",{ai_message: this.ai_message,user_message: this.last_message,company_id: this.$store.state.company.id,user_id: user_id})
                  .then(res => this.getMessages())
                    this.messages.push({
                      ai_message: this.ai_message,
                      user_message: message,
                    }).catch(err => {
                      this.waiting_message = "Upgrade your plan"
                    })
                    this.last_message = ""
                    this.waiting_message = ""
                    this.user_message = ""
                    this.ai_message = ""
                    let scroolInterval = setInterval(() => {
                      const elem = document.querySelector('#convoBody')
                      if (elem) {    
                        elem && (elem.scrollTop = elem.scrollHeight)
                        elem && (elem.behavior = 'smooth')  
                        clearInterval(scroolInterval)
                      }
                    },500)
                  })
                  .catch(error => {
                    console.error(error);
                  });
      }
    }
  }
  </script>
    
  <style scoped>
    .bot-wrapper {
      position: fixed;
      top: 90%;
      right: 10px;
    }
    .above-icon-wrapper {
      position: fixed;
      top: 84%;
      right: 10px;
    }
    .bot {
      width: 45px;
      cursor: pointer;
    }
    .above-icon {
      background: linear-gradient(180deg, #AF00FE 0%, #436CFF 100%);
      border: transparent;
      border-radius: 50%;
      padding: 19px 11px 7px;
      cursor: pointer;
    }
    .convo {
      /* height: 500px; */
      height: 70%;
      width: 400px;
      background-color: white;
      border: transparent;
      border-radius: 37px 37px 0px 37px;
      position: fixed;
      top: 23%;
      right: 60px;
      box-shadow: 0 0 0 9999px #000000b0;
      z-index: 100;
    }
    .convo-title {
      height: 12%;
      position: relative;
      background: linear-gradient(90deg, #436CFF 0%, #AF00FE 96.18%);
      border-radius: 37px 37px 0px 0px;
    }
    .convo-title span {
      position: absolute;
      right: 25px;
      top: 25%;
      color: white;
      font-size: 20px;
      cursor: pointer;
    }
    .convo-body {
      height: 74%;
      overflow: auto;
      padding: 2% 15px;
    }
    .convo-img {
      border: transparent;
      border-radius: 50%;
      width: 50px;
    }
    .right-convo {
      justify-content: end;
    }
    .left-convo p {
      background: rgba(222, 222, 222, 0.35);
      border-radius: 5px;
      border-bottom-right-radius: 20px;
      padding: 10px;
    }
    .right-convo p {
      background: #436CFF;
      border-radius: 5px;
      border-bottom-right-radius: 20px;
      padding: 10px;
      color: white;
    }
    .dots-3 {
    height: 18px;
    aspect-ratio: 2.5;
    --_g: no-repeat radial-gradient(farthest-side,#4d4a4a 90%,#0000);
    background:var(--_g), var(--_g), var(--_g), var(--_g);
    background-size: 20% 50%;
    animation:d3 1s infinite linear; 
    margin-top: 10px;
  }
  
  @keyframes d3 {
    0%     {background-position: calc(0*100%/3) 50% ,calc(1*100%/3) 50% ,calc(2*100%/3) 50% ,calc(3*100%/3) 50% }
    16.67% {background-position: calc(0*100%/3) 0   ,calc(1*100%/3) 50% ,calc(2*100%/3) 50% ,calc(3*100%/3) 50% }
    33.33% {background-position: calc(0*100%/3) 100%,calc(1*100%/3) 0   ,calc(2*100%/3) 50% ,calc(3*100%/3) 50% }
    50%    {background-position: calc(0*100%/3) 50% ,calc(1*100%/3) 100%,calc(2*100%/3) 0   ,calc(3*100%/3) 50% }
    66.67% {background-position: calc(0*100%/3) 50% ,calc(1*100%/3) 50% ,calc(2*100%/3) 100%,calc(3*100%/3) 0   }
    83.33% {background-position: calc(0*100%/3) 50% ,calc(1*100%/3) 50% ,calc(2*100%/3) 50% ,calc(3*100%/3) 100%}
    100%   {background-position: calc(0*100%/3) 50% ,calc(1*100%/3) 50% ,calc(2*100%/3) 50% ,calc(3*100%/3) 50% }
  }
    .convo-footer {
      height: 12%;
    }
    .convo-input {
      border: 1px solid linear-gradient(90deg, #436CFF 0%, #AF00FE 96.18%);
      border: 1px solid #B1B1B1;
      border-radius: 8px;
      width: 290px;
      padding: 10px;
      padding-right: 55px;
      color: #363434;
    }
    .convo-input::placeholder {
      color: #B1B1B1;
    }
    .send-icon, .other-icon {
      position: absolute;
      top: 0;
      bottom: 0;
      margin: auto;
      right: 0;
      translate: calc(-100% - 15px) 0;
    }
    .other-icon {
      right: 27px;
    }
    .convo-wrapper {
      margin-block: 7px;
    }
    /* @media only screen and (max-width: 1902px) {
      .convo {
        top: 30% !important;
      }
    }
    @media only screen and (max-width: 1712px) {
      .convo {
        top: 25% !important;
      }
    }
    @media only screen and (max-width: 1502px)  {
      .convo {
        top: 15% !important;
      }
    }
    @media only screen and (max-width: 1452px)  {
      .convo {
        top: 10% !important;
      }
    }
    @media only screen and (max-width: 1252px)  {
      .convo {
        top: 4% !important;
      }
    } */
  </style>
    