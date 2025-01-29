//Nicholas Benson
//Spring 2023
//CIS 231
//Cairn University 

import React, {useState} from "react";
import axios from "axios";
import "./App.css";

    const App = () => {
    const [pokemon, setPokemon] = useState("");
    const [pokemonData, setPokemonData] = useState([]);
    const [pokemonType, setPokemonType] = useState("");
    //Creates the variables that will be shown on the website//
    
    const getPokemon = async () => {
        const toArray = [];
        try{
            const url = `https://pokeapi.co/api/v2/pokemon/${pokemon}`
            //Responsible for the data that we are searching for//
            const res = await axios.get(url)
            toArray.push(res.data);
            setPokemonType(res.data.types[0].type.name);
            //Pulls the type of the pokemon from the API and shows it on the website//
            setPokemonData(toArray);
            console.log(res)
        } catch (e) {
            console.log(e)
            //In case of an error, the console of the react website will report the line where the error is taking place//
        }
    }

    const handleChange = (e) => {
        setPokemon(e.target.value.toLowerCase())
        //Reverts capital letters in the search box to lower case letters and allows both to search for the pokemon in question//
    }

    const handleSubmit = (e) => {
        e.preventDefault();
        //Prevents refresh of the page
        getPokemon();
    }

    //Returns all of the data from the API call into the website and the table that is created below//
    return (
        <div className="App">
            <form onSubmit={handleSubmit}>
                <label>
                    <input type="text" 
                    onChange={handleChange} 
                    //Creates the box in which we can type the names of the pokemon//
                    placeholder="Enter Pokemon Name"/>
                </label>
            </form>
            {pokemonData.map((data) => {
                return (
                    <div className="container">
                    <img src={data.sprites["front_default"]} />
                        <div className ="divTable">
                             <div className ="divTableBody">
                                <div className ="divTableRow">
                                    <div className ="divTableCell">Type</div>
                                    <div className ="divTableCell">{pokemonType}</div>
                            </div>
                            <div className ="divTableBody"></div>
                                <div className ="divTableRow">
                                    <div className ="divTableCell">Height</div>
                                    <div className ="divTableCell">
                                        {" "}
                                        {Math.round(data.height * 3.9)} Inches
                                    </div>
                            </div>
                            <div className ="divTableBody"></div>
                                <div className ="divTableRow">
                                    <div className ="divTableCell">Weight</div>
                                    <div className ="divTableCell">
                                        {" "}
                                        {Math.round(data.weight / 4.3 )} Pounds 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    )
                })}
        </div>
    )
}

export default App;
//Creates an export for the app to be exported to in the files//