public class ClickButton implements Button {
    private String backgroundColor = "white";
    private String textColor = "black";
    private boolean isClicked = false;

    @Override
    public void render() {
        System.out.println("Rendering ClickButton - Background: " 
            + backgroundColor + ", Text: " + textColor);
    }

    @Override
    public void onClick() {
        isClicked = !isClicked;
        if (isClicked) {
            backgroundColor = "darkblue";
            textColor = "white";
        } else {
            backgroundColor = "white";
            textColor = "black";
        }
        render();
    }
}