import './bootstrap';

Echo.channel('orders')
    .listen('RejectOrderData', (e) => {
        console.log(e.message);
    })
    .listen('AcceptOrderData', (e) => {
        console.log(e.message);
    });
