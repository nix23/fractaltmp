import React from 'react';
import { View } from 'react-native';

export default class SceneLayout extends React.Component {
    render() {
        const ModRow1 = this.props.ModRow1;
        const ModRow2 = this.props.ModRow2;

        return (
            <div>
                <div>
                    <ModRow1/>
                </div>

                <div>
                    <ModRow2/>
                </div>
            </div>
        );
    }
}