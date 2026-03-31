"use strict";(globalThis.webpackChunkpojo_accessibility=globalThis.webpackChunkpojo_accessibility||[]).push([[1032],{41300(e,o,t){t.d(o,{A:()=>c});var i=t(51609),a=t.n(i),n=t(11709),c=a().forwardRef((e,o)=>a().createElement(n.A,{...e,ref:o}))},62312(e,o,t){t.r(o),t.d(o,{default:()=>b});var i=t(5322),a=t(41300),n=t(85848),c=t(95231),s=t(96989),r=t(9626),l=t(50438),d=t(70276),p=t(86087),x=t(27723),h=t(10790);const m=(0,c.I)(i.A)`
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;

	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);

	width: 600px;
	max-width: 100%;
	height: 530px;
	background-color: ${({theme:e})=>e.palette.background.paper};
	padding: 20px;
	text-align: center;
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
`,b=()=>{const{isOpen:e,close:o}=(0,r.hS)(),{save:t}=(0,r.rX)();(0,p.useEffect)(()=>{e&&d.K.sendEvent(d.m.connectSuccess)},[e]);const i=async()=>{await t({ea11y_close_post_connect_modal:!0}),o()};return(0,h.jsx)(a.A,{open:e,onClose:i,children:(0,h.jsxs)(m,{container:!0,sx:{boxShadow:24},role:"dialog","aria-modal":"true","aria-labelledby":"post-connect-modal-title","aria-describedby":"post-connect-modal-description",children:[(0,h.jsx)(l.Zw,{}),(0,h.jsx)(n.A,{variant:"h5",color:"text.primary",marginTop:5,marginBottom:1,id:"post-connect-modal-title",children:(0,x.__)("You're all set","pojo-accessibility")}),(0,h.jsx)(n.A,{variant:"body2",sx:{width:"500px",maxWidth:"100%"},color:"text.primary",marginBottom:5,id:"post-connect-modal-description",children:(0,x.__)("Ally - Web Accessibility is now connected and ready to use on your site.","pojo-accessibility")}),(0,h.jsx)(s.A,{variant:"contained",sx:{padding:"8px 22px",width:"300px"},onClick:i,color:"primary",children:(0,x.__)("Done","pojo-accessibility")})]})})}}}]);