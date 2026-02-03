const btns = document.querySelectorAll('button');
let listProducts = [
    {id: 1,name: "Футбольный мяч",price: 2100,count: 53},
    {id: 2,name: "Беговые кросовки",price: 5400,count: 5},
    {id: 3,name: "Футболка",price: 1800,count: 28},
];
const elemList = document.querySelector('.listElems');

btns.forEach(elem => {
    elem.addEventListener('click',({target}) =>{
        switch(target.innerText){
            case 'Товары':
                createProducts();
                break;
            case 'Клиенты':
                createUsers();
                break;
            case 'Заказы':
                createOrders();
                break;
            default:
                break;

        }
    })
});

listProducts.forEach(elem => {
    const div = document.createElement('div');
    div.classList.add('product-card');
    div.innerHTML = `
    <div class="details">
                    <div class="info"><strong>ID:</strong>${elem.id}</div>
                    <h3>${elem.name}</h3>
                    <div class="price"><strong>Стоимость:</strong> ${elem.price} руб.</div>
                    <div class="info"><strong>Количество:</strong> ${elem.count}</div>
                    <div class="buttons">
                        <a href="EditProduct.html">Изменить</a>
                        <a class="delete">Удалить</a>
                    </div>
                </div>
    `
    elemList.appendChild(div);
});

const a = document.createElement('a');
a.href = "addProduct.html";
a.classList.add('add-user-link');
a.innerHTML = '<span class="plus-icon">+</span>';
elemList.appendChild(a);

function createProducts(){
    elemList.replaceChildren();
    listProducts.forEach(elem => {
        const div = document.createElement('div');
        div.classList.add('product-card');
        div.innerHTML = `
        <div class="details">
                        <div class="info"><strong>ID:</strong>${elem.id}</div>
                        <h3>${elem.name}</h3>
                        <div class="price"><strong>Стоимость:</strong> ${elem.price} руб.</div>
                        <div class="info"><strong>Количество:</strong> ${elem.count}</div>
                        <div class="buttons">
                            <a href="EditProduct.html">Изменить</a>
                            <a class="delete">Удалить</a>
                        </div>
                    </div>
        `
        elemList.appendChild(div);
    });
    const a = document.createElement('a');
    a.href = "addProduct.html";
    a.classList.add('add-user-link');
    a.innerHTML = '<span class="plus-icon">+</span>';
    elemList.appendChild(a);
   
}
function createUsers(){
    elemList.replaceChildren();
    const div = document.createElement('div');
    div.classList.add('user-card');
    div.innerHTML = `
    <div class="info"><strong>ID:</strong> 67890</div>
                <div class="name">Иванов Иван Иванович</div>
                <div class="role"><strong>Роль:</strong> Администратор</div>
                <div class="info"><strong>Телефон:</strong> +7 (123) 456-78-90</div>
                <div class="buttons">
                    <a href="EditUser.html" >Изменить</a>
                    <a class="delete">Удалить</a>
                </div>
    `
    elemList.appendChild(div);
    const a = document.createElement('a');
    a.href = "addUser.html";
    a.classList.add('add-user-link');
    a.innerHTML = '<span class="plus-icon">+</span>';
    elemList.appendChild(a);
}

function createOrders(){
    elemList.replaceChildren();
    const div = document.createElement('div');
    div.classList.add('data-card');
    div.innerHTML = `
    <div class="info"><strong>Номер:</strong> 001</div>
                <div class="sequence"><strong>Товары:</strong> 1, 2, 3, 4, 5</div>
                <div class="sum"><strong>Сумма:</strong> 15</div>
                <div class="date"><strong>Дата:</strong> 2023-10-01</div>
                <div class="buttons">
                    <a href="EditOrder.html" >Изменить</a>
                    <a class="delete">Удалить</a>
                </div>
    `
    elemList.appendChild(div);
}