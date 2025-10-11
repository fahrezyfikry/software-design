public class HoverButton implements Button {
    private String backgroundColor = "white";
    private boolean isHovered = false;

    @Override
    public void render() {
        System.out.println("Rendering HoverButton with background: " 
            + backgroundColor);
    }

    @Override
    public void onClick() {
        System.out.println("HoverButton clicked!");
    }

    public void onMouseEnter() {
        isHovered = true;
        backgroundColor = "lightblue";
        render();
    }

    public void onMouseLeave() {
        isHovered = false;
        backgroundColor = "white";
        render();
    }
}