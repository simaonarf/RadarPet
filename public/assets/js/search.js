const SEARCH_INPUT = document.getElementById('search');
const POSTS_CONTAINER = document.getElementById('posts-container');
const PAGINATOR_CONTAINER = document.querySelector('.pagination-container');
const API_URL = '/api/posts/search';
const DEBOUNCE_DELAY = 300;
let searchTimeout = null;

let INITIAL_POSTS_HTML = '';
if (POSTS_CONTAINER) {
    INITIAL_POSTS_HTML = POSTS_CONTAINER.innerHTML;
}

function buildPostCardHTML(post) {
    const showUrl = `/posts/${post.id}/show`;
    const destroyUrl = `/post/${post.id}`;

    const imageUrl = (post.thumbnail && post.thumbnail.path)
        ? post.thumbnail.path
        : 'https://placehold.co/600x400/eee/ccc?text=Sem+Foto';

    const buttonsHTML = `
        <div class="flex space-x-4 mt-4">                          
            <form action="${destroyUrl}" method="POST" class="m-0">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="bg-red-200 text-red-700 px-5 py-2 rounded-lg hover:bg-red-700 hover:text-red-200 transition">Delete</button>
            </form>
            <a href="/post/${post.id}/edit">
                <button type="button" class="bg-blue-200 text-blue-700 px-5 py-2 rounded-lg hover:bg-blue-700 hover:text-blue-200 transition">Edit</button>
            </a>
        </div>
    `;

    return `
        <div class="bg-white p-5 rounded-md shadow-lg w-full max-w-sm mx-auto">
            <a href="${showUrl}">
                <img src="${imageUrl}" alt="${post.title}" class="rounded-md w-full h-48 object-cover">
            </a>
            <h3 class="font-bold text-xl pb-2 pt-4">${post.title}</h3>
            <p class="text-gray-600 truncate">${post.description}</p>
            ${buttonsHTML}
        </div>
    `;
}

function renderPosts(posts) {
    let htmlContent = '';

    if (posts.length === 0) {
        htmlContent = '<div class="col-span-full text-center py-10 text-gray-500">Nenhum anúncio encontrado para esta busca.</div>';
    } else {
        posts.forEach(post => {
            htmlContent += buildPostCardHTML(post);
        });
    }
    POSTS_CONTAINER.innerHTML = htmlContent;
}

function fetchAndRenderPosts(keyword) {
    if (keyword.length === 0) {
        if (POSTS_CONTAINER) {
            POSTS_CONTAINER.innerHTML = INITIAL_POSTS_HTML;
        }
        if (PAGINATOR_CONTAINER) {
            PAGINATOR_CONTAINER.style.display = '';
        }
        return;
    }

    if (PAGINATOR_CONTAINER) {
        PAGINATOR_CONTAINER.style.display = 'none';
    }

    const apiUrl = API_URL + '?q=' + encodeURIComponent(keyword);

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) throw new Error('Erro ao buscar dados');
            return response.json();
        })
        .then(posts => {
            renderPosts(posts);
        })
        .catch(error => {
            console.error('Erro:', error);
            POSTS_CONTAINER.innerHTML = '<div class="col-span-full text-center text-red-600 p-8">Erro ao carregar.</div>';
        });
}

if (SEARCH_INPUT) {
    SEARCH_INPUT.addEventListener('input', function (e) {
        const keyword = e.target.value.trim();

        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            if (keyword.length >= 2 || keyword.length === 0) {
                fetchAndRenderPosts(keyword);
            }
        }, DEBOUNCE_DELAY);
    });
}