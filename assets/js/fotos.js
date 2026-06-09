function iniciais(nome){
    return nome.trim().split(' ')
        .filter(p => p.length > 1)
        .slice(0, 2)
        .map(p => p[0].toUpperCase())
        .join('');
}

function criarFotoJogador(nome, nomeCompleto = null, tamanho = 60){
    const nomeBusca = nomeCompleto || nome; // usa nome_completo para buscar, nome para exibir
    const wrapper = document.createElement('div');
    wrapper.className = 'jogador-foto jogador-placeholder';
    wrapper.style.width  = tamanho + 'px';
    wrapper.style.height = tamanho + 'px';
    wrapper.innerHTML = `<span>${iniciais(nome)}</span>`;

    fetch(`/PROJETO PSW/controllers/buscar_foto.php?nome=${encodeURIComponent(nomeBusca)}`)
        .then(r => r.json())
        .then(data => {
            if(data.foto){
                wrapper.innerHTML = `<img src="${data.foto}" alt="${nome}" onerror="this.parentElement.innerHTML='<span>${iniciais(nome)}</span>'">`;
                wrapper.classList.remove('jogador-placeholder');
                wrapper.classList.add('jogador-com-foto');
            }
        })
        .catch(() => {});

    return wrapper;
}