import React, { Component } from 'react';

const MATCH_TYPE_ALL = "all";
const MATCH_TYPE_START = "start";
const MATCH_TYPE_END = "end";

class Highlight extends Component {
    render() {
        let text = this.props.children;
        let searchVal = this.props.match;
        let matchType = MATCH_TYPE_ALL;

        if(this.props.matchType)
            matchType = this.props.matchType;

        let fakeDelimiter = "__NTECHDEVDOTCOM__";
        let searchValToSplit = text.replace(
            new RegExp(searchVal, 'g'), fakeDelimiter + searchVal + fakeDelimiter
        );
        let parts = searchValToSplit.split(fakeDelimiter).filter((part) => {
            return part != "";
        });

        let partsToRender = [];
        parts.map((part, i) => {
            let isFirst = (i == 0);
            let isLast = (i == parts.length - 1);

            if(part == searchVal) {
                if(isFirst && matchType == MATCH_TYPE_START ||
                   isFirst && matchType == MATCH_TYPE_ALL)
                    partsToRender.push({highlight: true, text: part});
                else if(!isFirst && !isLast && matchType == MATCH_TYPE_ALL)
                    partsToRender.push({highlight: true, text: part});
                else if(isLast && matchType == MATCH_TYPE_END)
                    partsToRender.push({highlight: true, text: part});
                else
                    partsToRender.push({highlight: false, text: part});
            }
            else
                partsToRender.push({highlight: false, text: part});
        });
        
        return (
            <span>
                {partsToRender.map((data, i) => {
                    return this.renderTextPart(data, i);
                })}
            </span>
        );
    }

    // @todo -> Configure MatchClass(Or pass from JS?)
    renderTextPart = ({highlight, text}, i) => {
        if(!highlight)
            return (<span key={i}>{text}</span>);

        return (
            <span key={i} style={{color: "#fc0e2f"}}>
                {text}
            </span>
        );
    }
};

export default Highlight;