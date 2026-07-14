class SearchParamUrl {
  buscar(variable) {
    const querystring = window.location.search;
    const params = new URLSearchParams(querystring);
    return params.get(variable);
  }
}

export { SearchParamUrl };
